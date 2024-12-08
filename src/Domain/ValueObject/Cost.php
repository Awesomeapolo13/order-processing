<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

readonly class Cost
{
    public function __construct(
        private string $totalCost,
        private string $totalCostWithDiscount,
    ) {
    }

    public function getTotalCost(): string
    {
        return $this->totalCost;
    }

    public function getTotalCostWithDiscount(): string
    {
        return $this->totalCostWithDiscount;
    }
}
