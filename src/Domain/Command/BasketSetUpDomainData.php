<?php

declare(strict_types=1);

namespace App\Domain\Command;

use App\Domain\ValueObject\DeliverySlot;
use App\Domain\ValueObject\Distance;
use App\Domain\ValueObject\OrderDate;

readonly class BasketSetUpDomainData
{
    public function __construct(
        public bool $isDelivery,
        public OrderDate $orderDate,
        public ?int $shopNumber = null,
        public ?DeliverySlot $slot = null,
        public ?Distance $distance = null,
        public bool $isFromUserShop = false
    ) {
    }
}
