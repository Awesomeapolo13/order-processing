<?php

declare(strict_types=1);

namespace App\Application\Command\AddProductFromCatalog;

use App\Application\Command\CommandInterface;

final readonly class AddProductFromCatalogCommand implements CommandInterface
{
    public function __construct(
        public int $userId,
        public string $supCode,
        public ?int $quantity,
        public ?string $weight,
        public bool $isPack = false,
    ) {
    }
}
