<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

readonly class Distance
{
    public const int LONG_DISTANCE = 50;

    private function __construct(
        private string $distance,
        private bool $longDuration,
        private bool $longDistance,
    ) {
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

    public static function create(string $distance, bool $longDuration): self
    {
        return new self(
            distance: $distance,
            longDuration: $longDuration,
            longDistance: (int) $distance > self::LONG_DISTANCE,
        );
    }
}
