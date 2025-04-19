<?php

declare(strict_types=1);

namespace App\Application\Command\SetUpBasket;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Command\BasketSetUpDomainData;
use App\Domain\Exception\BasketNotFoundException;
use App\Domain\Factory\BasketDeliveryFactory;
use App\Domain\Repository\BasketRepositoryInterface;
use App\Domain\ValueObject\DeliverySlot;
use App\Domain\ValueObject\Distance;
use App\Domain\ValueObject\OrderDate;
use App\Domain\ValueObject\Region;

class SetUpBasketCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly BasketRepositoryInterface $basketRepository,
        private readonly BasketDeliveryFactory $basketDeliveryFactory,
    ) {
    }

    public function __invoke(SetUpBasketCommand $command): void
    {
        /**
         * @TODO:
         *  1) Реализовать API для получения слота
         *  2) Реализовать API для получения магазина (или модуль)
         *  3) Составить логику определения, является ли корзина экспресс (через статический фабричный метод)
         */

        $region = new Region($command->regionCode);
        $deliverySLot = new DeliverySlot($command->slotNumber);
        $distance = Distance::create($command->distance, $command->longDuration);
        $orderDate = OrderDate::create($command->orderDate);
        $basket = $this->basketRepository->findActiveBasketByUserId($command->userId);

        if ($basket === null) {
            throw new BasketNotFoundException($command->userId, [
                'userId' => $command->userId,
                'region' => $region->getRegionCode(),
            ]);
        }

        $setUpData = new BasketSetUpDomainData(
            isDelivery: $command->isDelivery,
            orderDate: $orderDate,
            shopNumber: $command->shopNumber,
            deliverySlot: $deliverySLot,
            distance: $distance,
            isFromUserShop: false
        );
        $basket->setUpBasket($setUpData, $this->basketDeliveryFactory);
    }
}
