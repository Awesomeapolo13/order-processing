<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\Price;
use App\Domain\ValueObject\ProductQuantity;
use App\Domain\ValueObject\Weight;

class BasketItem
{
    private ?int $id = null;

    private ?Basket $basket = null;

    public function __construct(
        private string $supCode,
        private Price $perItemPrice,
        private Price $discountPrice,
        private Cost $slicingCost,
        private Cost $totalCost,
        private Cost $totalDiscountCost,
        private bool $isSlicing,
        private ProductQuantity $quantity,
        private int $addedBonus,
        private bool $isAvailableForOrder,
        private readonly ?\DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $updatedAt,
        private bool $isAlcohol,
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

    public function getBasket(): ?Basket
    {
        return $this->basket;
    }

    public function setBasket(?Basket $basket): self
    {
        $this->basket = $basket;

        return $this;
    }

    public function getSupCode(): string
    {
        return $this->supCode;
    }

    public function setSupCode(string $supCode): BasketItem
    {
        $this->supCode = $supCode;

        return $this;
    }

    public function getPerItemPrice(): Price
    {
        return $this->perItemPrice;
    }

    public function setPerItemPrice(Price $perItemPrice): BasketItem
    {
        $this->perItemPrice = $perItemPrice;

        return $this;
    }

    public function getDiscountPrice(): Price
    {
        return $this->discountPrice;
    }

    public function setDiscountPrice(Price $discountPrice): BasketItem
    {
        $this->discountPrice = $discountPrice;

        return $this;
    }

    public function getSlicingCost(): Cost
    {
        return $this->slicingCost;
    }

    public function setSlicingCost(Cost $slicingCost): BasketItem
    {
        $this->slicingCost = $slicingCost;

        return $this;
    }

    public function getTotalCost(): Cost
    {
        return $this->totalCost;
    }

    public function setTotalCost(Cost $totalCost): BasketItem
    {
        $this->totalCost = $totalCost;

        return $this;
    }

    public function getTotalDiscountCost(): Cost
    {
        return $this->totalDiscountCost;
    }

    public function setTotalDiscountCost(Cost $totalDiscountCost): BasketItem
    {
        $this->totalDiscountCost = $totalDiscountCost;

        return $this;
    }

    public function isSlicing(): bool
    {
        return $this->isSlicing;
    }

    public function setSlicing(bool $isSlicing): BasketItem
    {
        $this->isSlicing = $isSlicing;

        return $this;
    }

    public function getQuantity(): ProductQuantity
    {
        return $this->quantity;
    }

    public function getPieceQuantity(): ?int
    {
        return $this->quantity->getQuantity();
    }

    public function getWeight(): Weight
    {
        return $this->quantity->getWeight();
    }

    public function setQuantity(ProductQuantity $quantity): BasketItem
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getAddedBonus(): int
    {
        return $this->addedBonus;
    }

    public function setAddedBonus(int $addedBonus): BasketItem
    {
        $this->addedBonus = $addedBonus;

        return $this;
    }

    public function isAvailableForOrder(): bool
    {
        return $this->isAvailableForOrder;
    }

    public function setAvailableForOrder(bool $isAvailableForOrder): BasketItem
    {
        $this->isAvailableForOrder = $isAvailableForOrder;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): BasketItem
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isAlcohol(): bool
    {
        return $this->isAlcohol;
    }

    public function setIsAlcohol(bool $isAlcohol): BasketItem
    {
        $this->isAlcohol = $isAlcohol;

        return $this;
    }
}
