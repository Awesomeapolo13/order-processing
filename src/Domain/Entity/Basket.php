<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\BasketType;
use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\Region;
use App\Domain\ValueObject\Weight;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Basket
{
    private ?int $id = null;
    private ?BasketDelivery $delivery = null;
    /**
     * @var Collection<BasketItem>
     */
    private Collection $basketItems;

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
        private int $version = 1,
        private ?int $userId = null,
        private ?int $shopNum = null,
        private ?DateTimeImmutable $deletedAt = null,
    ) {
        $this->basketItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): Basket
    {
        $this->version = $version;

        return $this;
    }

    public function getDelivery(): ?BasketDelivery
    {
        return $this->delivery;
    }

    public function setDelivery(?BasketDelivery $delivery): Basket
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * @return  Collection<BasketItem>
     */
    public function getBasketItems(): Collection
    {
        return $this->basketItems;
    }

    public function addBasketItem(BasketItem $basketItem): Basket
    {
        if (!$this->basketItems->contains($basketItem)) {
            $this->basketItems->add($basketItem);
            $basketItem->setBasket($this);
        }

        return $this;
    }

    public function removeBasketItem(BasketItem $basketItem): Basket
    {
        if ($this->basketItems->removeElement($basketItem)) {
            $basketItem->setBasket(null);
        }

        return $this;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): Basket
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
