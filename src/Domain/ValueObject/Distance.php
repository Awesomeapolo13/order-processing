<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

readonly class Distance
{
    private bool $longDistance;

    public function __construct(
        private string $distance,
        private bool $longDuration,
    ) {
        $this->longDistance = (int)$this->distance > 50;
    }

    public function getDistance(): string
    {
        return $this->distance;
    }

    public function isLongDuration(): bool
    {
        return $this->longDuration;
    }

    public function isLongDistance(): bool
    {
        return $this->longDistance;
    }
}
