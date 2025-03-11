<?php

declare(strict_types=1);

namespace App\Infrastructure\ReadModel;

use App\Domain\ReadModel\ShortBasketItemReadModelInterface;
use App\Domain\ValueObject\Cost;

readonly class ShortBasketItemReadModel implements ShortBasketItemReadModelInterface
{
    public function __construct(
        private int $id,
        private string $supCode,
        private Cost $totalCost,
        private Cost $totalDiscountCost,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSupCode(): string
    {
        return $this->supCode;
    }

    public function getTotalCost(): Cost
    {
        return $this->totalCost;
    }

    public function getTotalDiscountCost(): Cost
    {
        return $this->totalDiscountCost;
    }
}
