<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Basket;
use App\Domain\ValueObject\BasketType;
use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\Region;
use App\Domain\ValueObject\Weight;

class BasketFactory
{
    public function createDefaultAuthorized(int $userId, Region $region): Basket
    {
        $createdAt = new \DateTimeImmutable();

        return new Basket(
            region: $region,
            type: BasketType::default(),
            orderDate: new \DateTime(),
            createdAt: $createdAt,
            updatedAt: $createdAt,
            slicingCost: Cost::zero(),
            totalCost: Cost::zero(),
            totalDiscountCost: Cost::zero(),
            weight: Weight::zero(),
            totalBonus: 0,
            userId: $userId,
            shopNum: null,
        );
    }
}
