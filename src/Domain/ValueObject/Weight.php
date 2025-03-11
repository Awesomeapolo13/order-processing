<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use InvalidArgumentException;

readonly class Weight
{
    public function __construct(
        private ?string $weight,
    ) {
        $this->assertWeight();
    }

    public function getWeight(): string
    {
        return $this->weight;
    }

    public function isNull(): bool
    {
        return !$this->weight === null;
    }

    public static function zero(): self
    {
        return new self('0.000');
    }

    public static function fromStringNullable(?string $weight): self
    {
        if ($weight === null) {
            return new self($weight);
        }

        if ('' === $weight) {
            return self::zero();
        }

        $formattedWeight = number_format((float) $weight, 3, '.', '');

        return new self($formattedWeight);
    }

    private function assertWeight(): void
    {
        if ($this->weight !== null && !preg_match('/^\d+\.\d{3}$/', $this->weight)) {
            throw new InvalidArgumentException(
                'Weight must be in format "0.000", got: ' . $this->weight
            );
        }
    }
}
