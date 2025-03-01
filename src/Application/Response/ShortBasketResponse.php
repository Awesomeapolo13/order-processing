<?php

declare(strict_types=1);

namespace App\Application\Response;

readonly class ShortBasketResponse
{
    /**
     * @param BasketItemShortResponse[] $basketItems
     */
    public function __construct(
        public int $id,
        public bool $isExpress,
        public bool $isDelivery,
        public bool $hasAlco,
        public \DateTimeImmutable $orderDate,
        public string $totalDiscountCost,
        public array $basketItems = [],
    ) {
    }
}
