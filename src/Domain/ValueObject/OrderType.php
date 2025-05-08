<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

readonly class OrderType extends BasketType
{
    public function __construct(
        bool $isDelivery,
        bool $isExpress,
        bool $hasAlcohol,
    ) {
        $this->checkOrderType($isDelivery, $hasAlcohol);
        parent::__construct($isDelivery, $isExpress, $hasAlcohol);
    }

    private function checkOrderType(bool $isDelivery, bool $hasAlcohol): void
    {
        if ($isDelivery && $hasAlcohol) {
            throw new \InvalidArgumentException('Alcohol order can not be delivery');
        }
    }
}
