<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

readonly class Price
{
    public function __construct(
        private string $price,
    ) {
    }

    public function getPrice(): string
    {
        return $this->price;
    }
}
