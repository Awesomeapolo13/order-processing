<?php

declare(strict_types=1);

namespace App\Domain\ReadModel;

use App\Domain\ValueObject\Cost;

interface ShortBasketItemReadModelInterface
{
    public function getId(): int;
    public function getSupCode(): string;
    public function getTotalCost(): Cost;
    public function getTotalDiscountCost(): Cost;
}
