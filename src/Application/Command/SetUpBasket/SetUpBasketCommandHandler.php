<?php

declare(strict_types=1);

namespace App\Application\Command\SetUpBasket;

use App\Application\Api\DeliverySlot\DeliverySlotApiInterface;
use App\Application\Api\DeliverySlot\FindDeliverySlotDTO;
use App\Application\Api\Shop\FindShopDTO;
use App\Application\Api\Shop\ShopApiInterface;
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
        private readonly ShopApiInterface $shopApi,
        private readonly DeliverySlotApiInterface $deliverySlotApi,
    ) {
    }

    public function __invoke(SetUpBasketCommand $command): void
    {
        /**
         * @TODO:
         *  1) Составить логику определения, является ли корзина экспресс (через статический фабричный метод)
         */

        $region = new Region($command->regionCode);
        $distance = isset($command->distance, $command->longDuration)
            ? Distance::create($command->distance, $command->longDuration)
            : null;
        $orderDate = OrderDate::create($command->orderDate);
        $shop = $this->shopApi->findShop(new FindShopDTO($command->shopNumber, (string)$command->regionCode));
        $deliverySLot = $this->deliverySlotApi->findSlot(new FindDeliverySlotDTO($command->slotNumber, (string)$command->regionCode));
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
            shop: $shop,
            deliverySlot: $deliverySLot,
            distance: $distance,
            isFromUserShop: false
        );
        $basket->setUpBasket($setUpData, $this->basketDeliveryFactory);
    }
}
