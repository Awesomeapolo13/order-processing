<?php

declare(strict_types=1);

namespace App\Application\Response;

readonly class BasketItemFullResponse
{
    public function __construct(
        public int $id,
        public string $supCode,
        public string $perItemPrice,
        public string $discountPrice,
        public string $slicingCost,
        public string $totalCost,
        public string $totalDiscountCost,
        public bool $isSlicing,
        public string $weight,
        public ?int $quantity,
        public int $addedBonus,
        public bool $isAvailableForOrder,
    ) {
    }
}
