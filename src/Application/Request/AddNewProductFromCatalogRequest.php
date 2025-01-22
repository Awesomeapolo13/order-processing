<?php

declare(strict_types=1);

namespace App\Application\Request;

readonly class AddNewProductFromCatalogRequest
{
    public function __construct(
        public string $supCode,
        public string $quantity,
        public bool $isPack,
    ) {
    }
}
