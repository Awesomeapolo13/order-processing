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
        private ?Weight $weight,
        private bool $isPack,
        private ?Weight $packWeight = null,
    ) {
    }

    public function getType(): ProductType
    {
        return $this->type;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getWeight(): ?Weight
    {
        return $this->weight;
    }

    public function isPack(): bool
    {
        return $this->isPack;
    }

    public function getPackWeight(): ?Weight
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

    private function assertQuantity(): void
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

        if ($this->weight !== null && $this->isPack) {
            throw new InvalidArgumentException('Piece product can not have weight unless it is pack');
        }

        if ($this->isPack && $this->packWeight === null) {
            throw new InvalidArgumentException('Piece product with pack must have pack weight');
        }
    }

    private function assertWeightTypeQuantity(): void
    {
        if ($this->weight === null) {
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

        if ($this->weight === null) {
            throw new InvalidArgumentException('Mixed product must have weight');
        }
    }
}
