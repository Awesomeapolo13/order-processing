<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\DeliverySlot;
use App\Domain\ValueObject\Distance;

class BasketDelivery
{
    private ?int $id = null;

    public function __construct(
        private DeliverySlot $slot,
        private bool $isFromUserShop,
        private Distance $distance,
        private Cost $deliveryCost,
        private Cost $deliveryDiscountCost,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getSlot(): DeliverySlot
    {
        return $this->slot;
    }

    public function setSlot(DeliverySlot $slot): BasketDelivery
    {
        $this->slot = $slot;

        return $this;
    }

    public function isFromUserShop(): bool
    {
        return $this->isFromUserShop;
    }

    public function setIsFromUserShop(bool $isFromUserShop): BasketDelivery
    {
        $this->isFromUserShop = $isFromUserShop;

        return $this;
    }

    public function getDistance(): Distance
    {
        return $this->distance;
    }

    public function setDistance(Distance $distance): BasketDelivery
    {
        $this->distance = $distance;

        return $this;
    }

    public function getDeliveryCost(): Cost
    {
        return $this->deliveryCost;
    }

    public function setDeliveryCost(Cost $deliveryCost): BasketDelivery
    {
        $this->deliveryCost = $deliveryCost;

        return $this;
    }

    public function getDeliveryDiscountCost(): Cost
    {
        return $this->deliveryDiscountCost;
    }

    public function setDeliveryDiscountCost(Cost $deliveryDiscountCost): BasketDelivery
    {
        $this->deliveryDiscountCost = $deliveryDiscountCost;

        return $this;
    }
}
