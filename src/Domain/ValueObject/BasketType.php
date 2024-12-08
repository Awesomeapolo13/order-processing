<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

readonly class BasketType
{
    public function __construct(
        protected bool $isDelivery,
        protected bool $isExpress,
        protected bool $hasAlcohol,
    ) {
    }

    public function isDelivery(): bool
    {
        return $this->isDelivery;
    }

    public function isExpress(): bool
    {
        return $this->isExpress;
    }

    public function hasAlcohol(): bool
    {
        return $this->hasAlcohol;
    }

    public function isExpressDelivery(): bool
    {
        return $this->isExpress && $this->isDelivery;
    }

    public function isExpressPickUp(): bool
    {
        return $this->isExpress && !$this->isDelivery;
    }

    public function isPreDelivery(): bool
    {
        return !$this->isExpress && $this->isDelivery;
    }

    public function isPrePickUp(): bool
    {
        return !$this->isExpress && !$this->isDelivery;
    }

    public function isAlcoholDelivery(): bool
    {
        return $this->hasAlcohol && $this->isDelivery;
    }
}
