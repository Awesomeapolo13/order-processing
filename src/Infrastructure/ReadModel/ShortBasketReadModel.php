<?php

declare(strict_types=1);

namespace App\Infrastructure\ReadModel;

use App\Domain\ReadModel\ShortBasketItemReadModelInterface;
use App\Domain\ReadModel\ShortBasketReadModelInterface;
use App\Domain\ValueObject\BasketType;
use App\Domain\ValueObject\Cost;

readonly class ShortBasketReadModel implements ShortBasketReadModelInterface
{
    /**
     * @param ShortBasketItemReadModelInterface[] $basketItems
     */
    public function __construct(
        private int $id,
        private BasketType $basketType,
        private \DateTimeImmutable $orderDate,
        private Cost $totalDiscountCost,
        private array $basketItems,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBasketType(): BasketType
    {
        return $this->basketType;
    }

    public function getOrderDate(): \DateTimeImmutable
    {
        return $this->orderDate;
    }

    public function getTotalDiscountCost(): Cost
    {
        return $this->totalDiscountCost;
    }

    /**
     * @return ShortBasketItemReadModelInterface[]
     */
    public function getBasketItems(): array
    {
        return $this->basketItems;
    }
}
