<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\BasketDelivery;
use App\Domain\Service\DeliveryCostCalculator;
use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\DeliverySlot;
use App\Domain\ValueObject\Distance;

class BasketDeliveryFactory
{
    public function __construct(
        private readonly DeliveryCostCalculator $deliveryCostCalculator,
    ) {
    }

    public function create(
        Cost $totalDiscountCost,
        DeliverySlot $slot,
        bool $isFromUserShop,
        Distance $distance,
    ): BasketDelivery {
        return  new BasketDelivery(
            slot: $slot,
            isFromUserShop: $isFromUserShop,
            distance: $distance,
            deliveryCost: $this->deliveryCostCalculator->calculateDeliveryFactCost($distance),
            deliveryDiscountCost: $this->deliveryCostCalculator->calculateDeliveryCostWithDiscount($totalDiscountCost, $distance),
        );
    }
}
