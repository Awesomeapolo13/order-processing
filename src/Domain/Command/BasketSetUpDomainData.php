<?php

declare(strict_types=1);

namespace App\Domain\Command;

use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\DeliverySlot;
use App\Domain\ValueObject\Distance;

readonly class BasketSetUpDomainData
{
    public function __construct(
        public bool $isDelivery,
        public \DateTimeInterface $orderDate,
        public ?int $shopNumber = null,
        public ?DeliverySlot $slot = null,
        public ?Distance $distance = null,
        public ?Cost $deliveryCost = null,
        public ?Cost $deliveryDiscountCost = null,
        public bool $isFromUserShop = false
    ) {
    }
}
