<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

readonly class DeliverySlot
{
    public function __construct(
        private int $slotNumber,
    ) {
    }

    public function getSlotNumber(): int
    {
        return $this->slotNumber;
    }
}
