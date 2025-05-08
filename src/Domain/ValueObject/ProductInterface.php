<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Enum\ProductType;

interface ProductInterface
{
    public function getSupCode(): string;

    public function getType(): ProductType;

    public function getPrice(): Price;

    public function getDiscountPrice(): Price;

    public function getSlicingPrice(): Price;

    public function getStockQuantity(): int;

    public function getStockWeight(): ?Weight;

    public function getCutCount(): int;

    public function isAvailableForOrder(): bool;

    public function isPiece(): bool;

    public function isWeight(): bool;

    public function isMixed(): bool;

    public function hasPackWeight(): bool;

    public function getPackWeight(): ?Weight;

    public function getMinimumWeight(): ?Weight;

    public function getMinimumQuantity(): ?int;

    public function getAverageWeight(): ?Weight;
}
