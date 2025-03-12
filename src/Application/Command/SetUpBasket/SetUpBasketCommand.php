<?php

declare(strict_types=1);

namespace App\Application\Command\SetUpBasket;

use App\Application\Command\CommandInterface;

readonly class SetUpBasketCommand implements CommandInterface
{
    public function __construct(
        public int $userId,
        public int $regionCode,
        public bool $isDelivery,
        public int $shopNumber,
        public ?int $slotNumber = null
    ) {
    }
}
