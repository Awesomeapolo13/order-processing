<?php

declare(strict_types=1);

namespace App\Application\Api\DeliverySlot;

readonly class FindDeliverySlotDTO
{
    public function __construct(
        public int $slotNumber,
        public string $regionCode
    ) {
    }
}
