<?php

declare(strict_types=1);

namespace App\Application\Command\UpdateBasket;

use App\Application\Api\Product\FindProductsDTO;
use App\Application\Api\Product\Product;
use App\Application\Api\Product\ProductApiInterface;
use App\Application\Command\CommandHandlerInterface;
use App\Application\Database\EntityManager\TransactionalEntityManagerInterface;
use App\Domain\Entity\Basket;
use App\Domain\Entity\BasketItem;
use App\Domain\Exception\BasketConcurrentModificationException;
use App\Domain\Exception\BasketForUpdatingNotFoundException;
use App\Domain\Factory\BasketFactory;
use App\Domain\Repository\BasketRepositoryInterface;
use App\Domain\Service\ProductCostCalculator;
use App\Domain\Service\SlicingCostCalculator;
use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\Region;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use Throwable;

class UpdateBasketHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly BasketRepositoryInterface $basketRepository,
        private readonly BasketFactory $basketFactory,
        private readonly TransactionalEntityManagerInterface $entityManager,
        private readonly ProductApiInterface $productApi,
        private readonly ProductCostCalculator $costCalculator,
        private readonly SlicingCostCalculator $slicingCostCalculator,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @return int - basket ID
     *
     * @throws Throwable
     */
    public function __invoke(UpdateBasketCommand $command): int
    {
        $userId = $command->userId;
        $region = $command->region;

        try {
            $this->entityManager->beginTransaction();

            $basket = $this->basketRepository->findActiveBasketByUserId($userId);

            if ($basket === null) {
                $this->logger->error('No basket for updating state found', [
                    'user_id' => $userId,
                    'region' => $region->getRegionCode(),
                ]);

                throw new BasketForUpdatingNotFoundException();
            }

            $this->entityManager->lock($basket, LockMode::OPTIMISTIC, $basket->getVersion());

            if (!$basket->getRegion()->isSame($region)) {
                $this->logger->info('Region mismatch, recreating basket', [
                    'user_id' => $userId,
                    'old_region' => $basket->getRegion()->getRegionCode(),
                    'new_region' => $region->getRegionCode(),
                ]);
                $basket = $this->recreateBasketWithNewRegion($userId, $region);
            }

            if (!$basket->getBasketItems()->isEmpty()) {
                $this->updateCosts($basket);
            }

            $this->entityManager->flush();
            $this->entityManager->commit();

            return $basket->getId();
        } catch (OptimisticLockException $exception) {
            $this->logger->error('Concurrent modification detected', [
                'user_id' => $userId,
                'exception' => $exception->getMessage()
            ]);

            throw new BasketConcurrentModificationException($userId, previous: $exception);
        } catch (Throwable $exception) {
            $this->entityManager->rollback();
            $this->logger->error('Unexpected error during basket processing', [
                'user_id' => $userId,
                'exception' => $exception->getMessage()
            ]);

            throw $exception;
        }
    }

    private function recreateBasketWithNewRegion(int $userId, Region $region): Basket
    {
        $this->basketRepository->setBasketDeleted($userId);
        $newBasket = $this->basketFactory->createDefaultAuthorized($userId, $region);
        $this->entityManager->persist($newBasket);

        return $newBasket;
    }

    private function updateCosts(Basket $basket): void
    {
        $oldTotalCost = $basket->getTotalCost();
        $oldTotalDiscountCost = $basket->getTotalCost();
        $supCodes = array_map(
            static fn (BasketItem $basketItem) => $basketItem->getSupCode(),
            $basket->getBasketItems()->toArray()
        );
        $products = $this->productApi->findProducts(
            new FindProductsDTO(
                $basket->getShopNum(),
                $basket->getRegion(),
                $supCodes
            )
        );
        $products = array_combine(
            array_map(static fn(Product $product) => $product->getSupCode(), $products),
            $products
        );
        $basketTotalCost = Cost::zero();
        $basketTotalDiscountCost = Cost::zero();

        $basket->getBasketItems()->forAll(
            function (BasketItem $basketItem) use ($products,  &$basketTotalCost,  &$basketTotalDiscountCost) {
                $product = $products[$basketItem->getSupCode()] ?? null;
                if (!isset($product) || !$product->isAvailableForOrder()) {
                    $basketItem->setAvailableForOrder(false);

                    return $basketItem;
                }

                $basketItem->setAvailableForOrder(true);
                $isSlicing = !$product->getSlicingPrice()->isZero();
                $slicingCost = Cost::zero();
                $totalCost = $this->costCalculator->calculateCost(
                    priceByQuant: $product->getPrice(),
                    quantity: $basketItem->getQuantity(),
                    weightQuant: $product->getMinimumWeight(),
                    averageWeight: $product->getAverageWeight(),
                );
                $totalDiscountCost = $this->costCalculator->calculateCost(
                    priceByQuant: $product->getDiscountPrice(),
                    quantity: $basketItem->getQuantity(),
                    weightQuant: $product->getMinimumWeight(),
                    averageWeight: $product->getAverageWeight(),
                );

                if (!$basketItem->getTotalCost()->equals($totalCost)) {
                    $this->logger->info('Basket item total cost updated', [
                        'basket_id' => $basketItem->getBasket()?->getId(),
                        'item_id' => $basketItem->getId(),
                        'old_cost' => $basketItem->getTotalCost()->getCost(),
                        'new_cost' => $totalCost->getCost()
                    ]);
                }
                if (!$basketItem->getTotalDiscountCost()->equals($totalDiscountCost)) {
                    $this->logger->info('Basket item total discount cost updated', [
                        'basket_id' => $basketItem->getBasket()?->getId(),
                        'item_id' => $basketItem->getId(),
                        'old_cost' => $basketItem->getTotalDiscountCost()->getCost(),
                        'new_cost' => $totalDiscountCost->getCost()
                    ]);
                }

                $basketItem
                    ->setPerItemPrice($product->getPrice())
                    ->setTotalCost($totalCost)
                    ->setTotalDiscountCost($totalDiscountCost)
                    ->setSlicing($isSlicing)
                    ->setSlicingCost($slicingCost);

                if ($isSlicing) {
                    $slicingCost = $this->slicingCostCalculator->calculateCost($product->getSlicingPrice(), $product->getCutCount());
                    $basketItem->setSlicingCost($slicingCost);
                }

                $basketTotalCost = $basketTotalCost
                    ->add($totalCost)
                    ->add($slicingCost);
                $basketTotalDiscountCost = $basketTotalDiscountCost
                    ->add($totalDiscountCost)
                    ->add($slicingCost);

                return true;
            }
        );

        if (!$basket->getTotalCost()->equals($oldTotalCost)) {
            $this->logger->info('Basket total cost updated', [
                'basket_id' => $basket->getId(),
                'old_cost' => $oldTotalCost->getCost(),
                'new_cost' => $basket->getTotalCost()->getCost()
            ]);
        }
        if (!$basket->getTotalDiscountCost()->equals($oldTotalDiscountCost)) {
            $this->logger->info('Basket total discount cost updated', [
                'basket_id' => $basket->getId(),
                'old_cost' => $oldTotalDiscountCost->getCost(),
                'new_cost' => $basket->getTotalDiscountCost()->getCost()
            ]);
        }

        $basket->setTotalCost($basketTotalCost);
        $basket->setTotalDiscountCost($basketTotalDiscountCost);
    }
}
