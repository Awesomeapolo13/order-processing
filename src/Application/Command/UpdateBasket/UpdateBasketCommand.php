<?php

declare(strict_types=1);

namespace App\Application\Command\UpdateBasket;

use App\Application\Command\CommandInterface;
use App\Domain\ValueObject\Region;

readonly class UpdateBasketCommand implements CommandInterface
{
    public function __construct(
        public int $userId,
        public Region $region,
    ) {
    }
}
