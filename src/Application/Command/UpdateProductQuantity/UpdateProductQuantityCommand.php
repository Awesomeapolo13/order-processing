<?php

declare(strict_types=1);

namespace App\Application\Command\UpdateProductQuantity;

use App\Application\Command\CommandInterface;

readonly class UpdateProductQuantityCommand implements CommandInterface
{
    public function __construct(
        public int $userId,
        public int $regionCode,
        public string $supCode,
        public string $quantity,
        public string $weight,
        public bool $isPack,
    ) {
    }
}
