<?php

declare(strict_types=1);

namespace App\Infrastructure\ReadModel\Hydrator;

use App\Domain\ReadModel\ShortBasketReadModelInterface;
use App\Domain\ValueObject\BasketType;
use App\Domain\ValueObject\Cost;
use App\Infrastructure\ReadModel\ShortBasketItemReadModel;
use App\Infrastructure\ReadModel\ShortBasketReadModel;

class ShortBasketHydrator
{
    /**
     * @throws \DateMalformedStringException
     */
    public function hydrate(array $data): ?ShortBasketReadModelInterface
    {
        if ($data === [] || !isset($data[0]) || !$this->isRequiredFieldsExists($data[0])) {
            return null;
        }
        $basketItems = [];

        foreach ($data as $item) {
            if ($this->isBasketItemsDataExists($item)) {
                $basketItems[] = new ShortBasketItemReadModel(
                    (int)$item['basket_item_id'],
                    $item['sup_code'],
                    Cost::fromString($item['total_cost']),
                    Cost::fromString($item['total_discount_cost']),
                );
            }
        }
        
        return new ShortBasketReadModel(
            (int)$data[0]['basket_id'],
            new BasketType($data[0]['is_express'], $data[0]['is_delivery'], $data[0]['has_alco']),
            new \DateTimeImmutable($data[0]['order_date']),
            Cost::fromString($data[0]['total_discount_cost']),
            $basketItems,
        );
    }

    private function isBasketItemsDataExists(array $item): bool
    {
        return isset($item['basket_item_id'], $item['sup_code'], $item['total_cost'], $item['total_discount_cost']);
    }

    private function isRequiredFieldsExists(array $basket): bool
    {
        return isset($basket['basket_id'], $basket['is_express'], $basket['has_alco'], $basket['is_delivery'],  $basket['order_date']);
    }
}
