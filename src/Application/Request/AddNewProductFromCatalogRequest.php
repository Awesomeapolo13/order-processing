<?php

declare(strict_types=1);

namespace App\Application\Request;

readonly class AddNewProductFromCatalogRequest
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
