<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

interface ProductInterface
{
    public function isPiece(): bool;
    public function isWeight(): bool;
    public function isMixed(): bool;
    public function hasPackWeight(): bool;
    public function getPackWeight(): ?Weight;
    public function getMinimumWeight(): ?Weight;
    public function getMinimumQuantity(): ?int;
}
