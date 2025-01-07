<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Assembler\ResponseAssemblerInterface;
use App\Application\Request\GetFullBasketRequest;
use App\Application\Response\GetFullBasketResponse;
use App\Domain\Entity\Basket;
use App\Domain\Exception\BasketConcurrentModificationException;
use App\Domain\Factory\BasketFactory;
use App\Domain\Repository\BasketRepositoryInterface;
use App\Domain\ValueObject\Region;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use Throwable;

final class GetFullBasketUseCase
{
    public function __construct(
        private readonly BasketRepositoryInterface $basketRepository,
        private readonly ResponseAssemblerInterface $responseAssembler,
        private readonly BasketFactory $basketFactory,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(GetFullBasketRequest $dto): GetFullBasketResponse
    {
        $userId = $dto->userId;
        $region = new Region($dto->regionCode);

        try {
            return $this->processBasketRequest($userId, $region);
        } catch (OptimisticLockException $exception) {
            $this->logger->error('Concurrent modification detected', [
                'user_id' => $userId,
                'exception' => $exception->getMessage()
            ]);

            throw new BasketConcurrentModificationException($userId, previous: $exception);
        } catch (Throwable $exception) {
            $this->logger->error('Unexpected error during basket processing', [
                'user_id' => $dto->userId,
                'exception' => $exception->getMessage()
            ]);

            throw $exception;
        }
    }

    private function processBasketRequest(int $userId, Region $region): GetFullBasketResponse
    {
        try {
            $basket = $this->getOrCreateBasket($userId, $region);

            $this->entityManager->flush();
            $this->entityManager->commit();

            return $this->responseAssembler->createResponse($basket);
        } catch (Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }
    }

    /**
     * @throws OptimisticLockException
     */
    private function getOrCreateBasket(int $userId, Region $region): Basket
    {
        $this->entityManager->beginTransaction();

        $basket = $this->basketRepository->findActiveBasketByUserId($userId);

        if ($basket === null) {
            $this->logger->info('No basket found, creating new', [
                'user_id' => $userId,
                'region' => $region->getRegionCode(),
            ]);

            $basket = $this->basketFactory->createDefaultAuthorized($userId, $region);
            $this->entityManager->persist($basket);
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

        return $basket;
    }

    private function recreateBasketWithNewRegion(int $userId, Region $region): Basket
    {
        $this->basketRepository->setBasketDeleted($userId);
        $newBasket = $this->basketFactory->createDefaultAuthorized($userId, $region);
        $this->entityManager->persist($newBasket);

        return $newBasket;
    }
}
