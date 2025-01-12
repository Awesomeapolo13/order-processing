<?php

declare(strict_types=1);

namespace App\Application\Command\UpdateBasket;

use App\Application\Command\CommandHandlerInterface;
use App\Application\Database\EntityManager\TransactionalEntityManagerInterface;
use App\Domain\Entity\Basket;
use App\Domain\Exception\BasketConcurrentModificationException;
use App\Domain\Exception\BasketForUpdatingNotFoundException;
use App\Domain\Factory\BasketFactory;
use App\Domain\Repository\BasketRepositoryInterface;
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

            $this->entityManager->lock($basket, LockMode::OPTIMISTIC);

            if (!$basket->getRegion()->isSame($region)) {
                $this->logger->info('Region mismatch, recreating basket', [
                    'user_id' => $userId,
                    'old_region' => $basket->getRegion()->getRegionCode(),
                    'new_region' => $region->getRegionCode(),
                ]);
                $basket = $this->recreateBasketWithNewRegion($userId, $region);
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
}
