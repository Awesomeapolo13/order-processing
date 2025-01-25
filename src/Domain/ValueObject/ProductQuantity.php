<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Enum\ProductType;
use InvalidArgumentException;

readonly class ProductQuantity
{
    private function __construct(
        private ProductType $type,
        private ?int $quantity,
        private Weight $weight,
        private bool $isPack,
        private Weight $packWeight,
    ) {
    }

    public static function piece(int $quantity, Weight $packWeight, bool $isPack = false): self
    {
        $productQuantity = new self(
            type: ProductType::PIECE,
            quantity: $quantity,
            weight: Weight::fromStringNullable(null),
            isPack: $isPack,
            packWeight: $packWeight
        );

        $productQuantity->assertQuantity();

        return $productQuantity;
    }

    public static function weight(Weight $weight): self
    {
        $productQuantity = new self(
            type: ProductType::WEIGHT,
            quantity: null,
            weight: $weight,
            isPack: false,
            packWeight: Weight::fromStringNullable(null),
        );

        $productQuantity->assertQuantity();

        return $productQuantity;
    }

    public static function mixed(int $quantity, Weight $weight): self
    {
        $productQuantity = new self(
            type: ProductType::MIXED,
            quantity: $quantity,
            weight: $weight,
            isPack: false,
            packWeight: Weight::fromStringNullable(null),
        );

        $productQuantity->assertQuantity();

        return $productQuantity;
    }

    public static function fromOrm(
        ProductType $type,
        ?int $quantity,
        Weight $weight,
        bool $isPack,
        Weight $packWeight
    ): self {
        return new self($type, $quantity, $weight, $isPack, $packWeight);
    }

    public static function fromProduct(
        ?int $quantity,
        ?string $weight,
        bool $isPack,
        ProductInterface $product,
    ): self {
        if ($product->isPiece() && $quantity === null) {
            throw new InvalidArgumentException('Quantity is required for piece products');
        }

        if ($product->isWeight() && $weight === null) {
            throw new InvalidArgumentException('Weight is required for weight products');
        }

        if ($product->isMixed() && ($quantity === null || $weight === null)) {
            throw new InvalidArgumentException('Both quantity and weight are required for mixed products');
        }

        if ($isPack && !$product->hasPackWeight()) {
            throw new InvalidArgumentException('Product does not support pack purchase');
        }

        return match (true) {
            $product->isPiece() => self::piece(
                quantity: $quantity,
                packWeight: $isPack ? $product->getPackWeight() : null,
                isPack: $isPack,
            ),
            $product->isWeight() => self::weight(Weight::fromStringNullable($weight)),
            default => self::mixed(
                quantity: $quantity,
                weight: Weight::fromStringNullable($weight)
            ),
        };
    }

    public function getType(): ProductType
    {
        return $this->type;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getWeight(): Weight
    {
        return $this->weight;
    }

    public function isPack(): bool
    {
        return $this->isPack;
    }

    public function getPackWeight(): Weight
    {
        return $this->packWeight;
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

    public function assertQuantity(): void
    {
        match ($this->type) {
            ProductType::PIECE => $this->assertPieceTypeQuantity(),
            ProductType::WEIGHT => $this->assertWeightTypeQuantity(),
            ProductType::MIXED => $this->assertMixedTypeQuantity(),
        };
    }

    private function assertPieceTypeQuantity(): void
    {
        if ($this->quantity === null || $this->quantity < 1) {
            throw new InvalidArgumentException('Piece product must be a positive integer');
        }

        if (!$this->weight->isNull() && $this->isPack) {
            throw new InvalidArgumentException('Piece product can not have weight unless it is pack');
        }

        if ($this->isPack && $this->packWeight->isNull()) {
            throw new InvalidArgumentException('Piece product with pack must have pack weight');
        }
    }

    private function assertWeightTypeQuantity(): void
    {
        if ($this->weight->isNull()) {
            throw new InvalidArgumentException('Weight product must have weight');
        }

        if ($this->quantity !== null) {
            throw new InvalidArgumentException('Weight product cannot have quantity');
        }
    }

    private function assertMixedTypeQuantity(): void
    {
        if ($this->quantity === null || $this->quantity <= 0) {
            throw new InvalidArgumentException('Mixed product must have positive quantity');
        }

        if ($this->weight->isNull()) {
            throw new InvalidArgumentException('Mixed product must have weight');
        }
    }
}
