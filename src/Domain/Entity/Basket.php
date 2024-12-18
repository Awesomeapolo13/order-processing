<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\BasketType;
use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\Region;
use App\Domain\ValueObject\Weight;
use DateTimeImmutable;
use DateTimeInterface;

class Basket
{
    private int $id;

    public function __construct(
        private Region $region,
        private BasketType $type,
        private DateTimeInterface $orderDate,
        private readonly ?DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $updatedAt,
        private Cost $slicingCost,
        private Cost $totalCost,
        private Cost $totalDiscountCost,
        private Weight $weight,
        private int $totalBonus = 0,
        private ?int $userId = null,
        private ?int $shopNum = null,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): Basket
    {
        $this->userId = $userId;

        return $this;
    }

    public function getShopNum(): ?int
    {
        return $this->shopNum;
    }

    public function setShopNum(?int $shopNum): Basket
    {
        $this->shopNum = $shopNum;

        return $this;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): Basket
    {
        $this->region = $region;

        return $this;
    }

    public function getType(): BasketType
    {
        return $this->type;
    }

    public function setType(BasketType $type): Basket
    {
        $this->type = $type;

        return $this;
    }

    public function getOrderDate(): DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(DateTimeInterface $orderDate): Basket
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): Basket
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSlicingCost(): Cost
    {
        return $this->slicingCost;
    }

    public function setSlicingCost(Cost $slicingCost): Basket
    {
        $this->slicingCost = $slicingCost;

        return $this;
    }

    public function getTotalCost(): Cost
    {
        return $this->totalCost;
    }

    public function setTotalCost(Cost $totalCost): Basket
    {
        $this->totalCost = $totalCost;

        return $this;
    }

    public function getTotalDiscountCost(): Cost
    {
        return $this->totalDiscountCost;
    }

    public function setTotalDiscountCost(Cost $totalDiscountCost): Basket
    {
        $this->totalDiscountCost = $totalDiscountCost;

        return $this;
    }

    public function getWeight(): Weight
    {
        return $this->weight;
    }

    public function setWeight(Weight $weight): Basket
    {
        $this->weight = $weight;

        return $this;
    }

    public function getTotalBonus(): int
    {
        return $this->totalBonus;
    }

    public function setTotalBonus(int $totalBonus): Basket
    {
        $this->totalBonus = $totalBonus;

        return $this;
    }
}
