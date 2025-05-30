<?php

declare(strict_types=1);

namespace App\Application\Response;

readonly class GetFullBasketResponse
{
    // ToDo: Need to add an information about shop address and other
    /**
     * @param BasketItemFullResponse[] $basketItems
     */
    public function __construct(
        public int $id,
        public bool $isExpress,
        public bool $isDelivery,
        public \DateTimeInterface $orderDate,
        public string $slicingCost,
        public string $totalCost,
        public string $totalDiscountCost,
        public string $weight,
        public int $totalBonus,
        public ?DeliveryResponse $delivery = null,
        public array $basketItems = [],
    ) {
    }
}
