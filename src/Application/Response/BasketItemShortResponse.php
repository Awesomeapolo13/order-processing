<?php

declare(strict_types=1);

namespace App\Application\Response;

readonly class BasketItemShortResponse
{
    public function __construct(
        public int $id,
        public string $supCode,
        public string $totalCost,
        public string $totalDiscountCost,
    ) {
    }
}
