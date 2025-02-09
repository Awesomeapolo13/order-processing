<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Event\EventInterface;

class ProductAddedToBasketFromCatalogEvent implements EventInterface
{
    public function __construct(
        public int $userId,
        public int $region,
        public int $basketId,
        public int $basketItemId,
        public string $supCode,
        public ?int $quantity,
        public ?string $weight,
        public bool $isPack,
    ) {
    }
}
