<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use InvalidArgumentException;

readonly class Cost
{
    public function __construct(
        private string $cost,
    ) {
        $this->assertCost();
    }

    public function getCost(): string
    {
        return $this->cost;
    }

    public static function zero(): self
    {
        return new self('0.00');
    }

    public static function fromString(string $cost): self
    {
        if ('' === $cost) {
            return self::zero();
        }

        $formattedCost = number_format((float) $cost, 2, '.', '');

        return new self($formattedCost);
    }

    private function assertCost(): void
    {
        if (!preg_match('/^\d+\.\d{2}$/', $this->cost)) {
            throw new InvalidArgumentException(
                'Cost must be in format "0.00", got: ' . $this->cost
            );
        }
    }
}
