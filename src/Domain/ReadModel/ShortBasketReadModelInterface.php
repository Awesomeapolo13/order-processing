<?php

declare(strict_types=1);

namespace App\Domain\ReadModel;

use App\Domain\ValueObject\BasketType;
use App\Domain\ValueObject\Cost;
use DateTimeImmutable;

interface ShortBasketReadModelInterface
{
    public function getId(): int;
    public function getBasketType(): BasketType;
    public function getOrderDate(): ?DateTimeImmutable;
    public function getTotalDiscountCost(): Cost;
    /**
     * @return ShortBasketItemReadModelInterface[]
     */
    public function getBasketItems(): array;
}
