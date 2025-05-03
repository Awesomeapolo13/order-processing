<?php

declare(strict_types=1);

namespace App\Application\Command\CreateBasket;

use App\Application\Command\CommandInterface;
use App\Domain\ValueObject\Region;

readonly class CreateBasketCommand implements CommandInterface
{
    public function __construct(
        public int $userId,
        public Region $region
    ) {
    }
}
