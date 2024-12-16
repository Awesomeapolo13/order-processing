<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

readonly class Weight
{
    public function __construct(
        private string $weight,
    ) {
    }

    public function getWeight(): string
    {
        return $this->weight;
    }
}
