<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\BasketItem;
use App\Domain\Service\ProductCostCalculator;
use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\ProductInterface;
use App\Domain\ValueObject\ProductQuantity;

class BasketItemFactory
{
    public function __construct(
        private readonly ProductCostCalculator $costCalculator,
    ) {
    }

    public function createByProductFromCatalog(
        ProductInterface $product,
        ?int $quantity,
        ?string $weight,
        bool $isPack,
    ): BasketItem {
        $productQuantity = ProductQuantity::fromProduct(
            quantity: $quantity,
            weight: $weight,
            isPack: $isPack,
            product: $product,
        );

        $totalCost = $this->costCalculator->calculateCost(
            $product->getPrice(),
            $productQuantity,
            $product->getMinimumWeight(),
            $product->getAverageWeight(),
        );

        $totalDiscountCost = $this->costCalculator->calculateCost(
            $product->getDiscountPrice(),
            $productQuantity,
            $product->getMinimumWeight(),
            $product->getAverageWeight(),
        );

        $createdAt = new \DateTimeImmutable();

        return new BasketItem(
            supCode: $product->getSupCode(),
            perItemPrice: $product->getPrice(),
            discountPrice: $product->getDiscountPrice(),
            slicingCost: Cost::zero(),
            totalCost: $totalCost,
            totalDiscountCost: $totalDiscountCost,
            isSlicing: !$product->getSlicingPrice()->isZero(),
            quantity: $productQuantity,
            addedBonus: 0,
            isAvailableForOrder: $product->isAvailableForOrder(),
            createdAt: $createdAt,
            updatedAt: $createdAt,
        );
    }
}
