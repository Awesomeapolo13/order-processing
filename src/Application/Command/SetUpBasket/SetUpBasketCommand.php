<?php

declare(strict_types=1);

namespace App\Application\Command\SetUpBasket;

use App\Application\Command\CommandInterface;

readonly class SetUpBasketCommand implements CommandInterface
{
    public function __construct(
        public int $userId,
        public int $regionCode,
        public \DateTimeImmutable $orderDate,
        public bool $isDelivery,
        public ?int $shopNumber = null,
        public ?int $slotNumber = null,
        public ?string $distance = null,
        public ?bool $longDuration = null,
    ) {
    }
}
