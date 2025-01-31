<?php

declare(strict_types=1);

namespace App\Application\Api\Product;

use App\Domain\Enum\ProductType;
use App\Domain\ValueObject\Price;
use App\Domain\ValueObject\ProductInterface;
use App\Domain\ValueObject\Weight;

readonly class Product implements ProductInterface
{
    public function __construct(
        public string $supCode,
        public ProductType $type,
        public Price $price,
        public Price $discountPrice,
        public int $stockQuantity,
        public ?Weight $stockWeight,
        public ?int $minimumQuantity,
        public ?Weight $minimumWeight,
        public ?Weight $packWeight,
        public bool $isAvailableForOrder,
    ) {
    }

    public function getSupCode(): string
    {
        return $this->supCode;
    }

    public function getType(): ProductType
    {
        return $this->type;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getDiscountPrice(): Price
    {
        return $this->discountPrice;
    }

    public function getStockQuantity(): int
    {
        return $this->stockQuantity;
    }

    public function getStockWeight(): ?Weight
    {
        return $this->stockWeight;
    }

    public function isAvailableForOrder(): bool
    {
        return $this->isAvailableForOrder;
    }

    public function isPiece(): bool
    {
        return $this->type === ProductType::PIECE;
    }

    public function isWeight(): bool
    {
        return $this->type === ProductType::WEIGHT;
    }

    public function isMixed(): bool
    {
        return $this->type === ProductType::MIXED;
    }

    public function hasPackWeight(): bool
    {
        return $this->packWeight !== null;
    }

    public function getPackWeight(): ?Weight
    {
        return $this->packWeight;
    }

    public function getMinimumWeight(): ?Weight
    {
        return $this->minimumWeight;
    }

    public function getMinimumQuantity(): ?int
    {
        return $this->minimumQuantity;
    }
}
