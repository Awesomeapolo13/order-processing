<?php

declare(strict_types=1);

namespace App\Domain\Command;

use App\Domain\ValueObject\DeliverySlot;
use App\Domain\ValueObject\Distance;
use App\Domain\ValueObject\OrderDate;
use App\Domain\ValueObject\ShopInterface;

readonly class BasketSetUpDomainData
{
    public function __construct(
        public bool $isDelivery,
        public OrderDate $orderDate,
        public ?ShopInterface $shop = null,
        public ?DeliverySlot $deliverySlot = null,
        public ?Distance $distance = null,
        public bool $isFromUserShop = false
    ) {
    }
}
