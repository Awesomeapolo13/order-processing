<?php

declare(strict_types=1);

namespace App\Application\Assembler\Response;

use App\Application\Assembler\ResponseAssemblerInterface;
use App\Application\Response\BasketItemFullResponse;
use App\Application\Response\DeliveryResponse;
use App\Application\Response\GetFullBasketResponse;
use App\Domain\Entity\Basket;
use App\Domain\Entity\BasketDelivery;
use App\Domain\Entity\BasketItem;

/**
 * @implements ResponseAssemblerInterface<Basket, GetFullBasketResponse>
 */
class GetFullBasketResponseAssembler implements ResponseAssemblerInterface
{
    public function createResponse(object $entity): object
    {
        $basketItems = [];
        $delivery = $entity->getDelivery()
            ? $this->createDeliveryResponse($entity->getDelivery())
            : null;

        foreach ($entity->getBasketItems() as $basketItem) {
            $basketItems[] = $this->createBasketItem($basketItem);
        }

        return new GetFullBasketResponse(
            $entity->getId(),
            $entity->getType()->isExpress(),
            $entity->getType()->isDelivery(),
            $entity->getOrderDate(),
            $entity->getSlicingCost()->getCost(),
            $entity->getTotalCost()->getCost(),
            $entity->getTotalDiscountCost()->getCost(),
            $entity->getWeight()->getWeight(),
            $entity->getTotalBonus(),
            $delivery,
            $basketItems,
        );
    }

    private function createDeliveryResponse(BasketDelivery $delivery): DeliveryResponse
    {
        return new DeliveryResponse(
            $delivery->getSlot()->getSlotNumber(),
            $delivery->isFromUserShop(),
            $delivery->getDistance()->getDistance(),
            $delivery->getDeliveryCost()->getCost(),
            $delivery->getDeliveryDiscountCost()->getCost(),
        );
    }

    private function createBasketItem(BasketItem $basketItem): BasketItemFullResponse
    {
        return new BasketItemFullResponse(
            id: $basketItem->getId(),
            supCode: $basketItem->getSupCode(),
            perItemPrice: $basketItem->getPerItemPrice()->getPrice(),
            discountPrice: $basketItem->getDiscountPrice()->getPrice(),
            slicingCost: $basketItem->getSlicingCost()->getCost(),
            totalCost: $basketItem->getTotalCost()->getCost(),
            totalDiscountCost: $basketItem->getTotalDiscountCost()->getCost(),
            isSlicing: $basketItem->isSlicing(),
            weight: $basketItem->getWeight()->getWeight(),
            quantity: $basketItem->getPieceQuantity(),
            addedBonus: $basketItem->getAddedBonus(),
            isAvailableForOrder: $basketItem->isAvailableForOrder(),
        );
    }
}
