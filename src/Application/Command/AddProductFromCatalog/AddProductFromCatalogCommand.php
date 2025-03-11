<?php

declare(strict_types=1);

namespace App\Application\Command\AddProductFromCatalog;

use App\Application\Command\CommandInterface;
use App\Domain\ValueObject\Region;

final readonly class AddProductFromCatalogCommand implements CommandInterface
{
    public function __construct(
        public int $userId,
        public Region $region,
        public string $supCode,
        public ?int $quantity,
        public ?string $weight,
        public bool $isPack = false,
    ) {
    }
}
