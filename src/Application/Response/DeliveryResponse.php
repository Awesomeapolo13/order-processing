<?php

declare(strict_types=1);

namespace App\Application\Response;

readonly class DeliveryResponse
{
    public function __construct(
        public int $slot,
        public bool $isFromUserShop,
        public string $distance,
        public string $deliveryCost,
        public string $deliveryDiscountCost,
    ) {
    }
}
