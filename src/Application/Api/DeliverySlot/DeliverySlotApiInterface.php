<?php

declare(strict_types=1);

namespace App\Application\Api\DeliverySlot;

use App\Domain\ValueObject\DeliverySlot;

interface DeliverySlotApiInterface
{
    public function findSlot(FindDeliverySlotDTO $dto): ?DeliverySlot;
}
