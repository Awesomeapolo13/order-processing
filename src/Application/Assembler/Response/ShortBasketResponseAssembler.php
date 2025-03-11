<?php

declare(strict_types=1);

namespace App\Application\Assembler\Response;

use App\Application\Assembler\ResponseAssemblerInterface;
use App\Application\Response\BasketItemShortResponse;
use App\Application\Response\ShortBasketResponse;
use App\Domain\ReadModel\ShortBasketItemReadModelInterface;
use App\Domain\ReadModel\ShortBasketReadModelInterface;

/**
 * @implements ResponseAssemblerInterface<ShortBasketReadModelInterface, ShortBasketResponse>
 */
class ShortBasketResponseAssembler implements ResponseAssemblerInterface
{
    public function createResponse(object $entity): object
    {
        $basketItems = [];

        foreach ($entity->getBasketItems() as $basketItem) {
            $basketItems[] = $this->createBasketItem($basketItem);
        }

        return new ShortBasketResponse(
            $entity->getId(),
            $entity->getBasketType()->isExpress(),
            $entity->getBasketType()->isDelivery(),
            $entity->getBasketType()->hasAlcohol(),
            $entity->getOrderDate(),
            $entity->getTotalDiscountCost()->getCost(),
            $basketItems,
        );
    }

    private function createBasketItem(ShortBasketItemReadModelInterface $basketItem): BasketItemShortResponse
    {
        return new BasketItemShortResponse(
            $basketItem->getId(),
            $basketItem->getSupCode(),
            $basketItem->getTotalCost()->getCost(),
            $basketItem->getTotalDiscountCost()->getCost()
        );
    }
}
