<?php

declare(strict_types=1);

namespace App\Application\Request;

readonly class SetUpBasketRequest
{
    public function __construct(
        public int $regionCode,
        public int $userId,
        public string $orderDate,
        public bool $isDelivery,
        public ?int $shopNumber,
        public ?int $slotNumber = null,
        public ?string $distance = null,
        public ?bool $longDuration = null,
    ) {
    }
}
