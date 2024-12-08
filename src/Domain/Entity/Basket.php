<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Infrastructure\Repository\BasketRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BasketRepository::class)]
class Basket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $userId = null;

    #[ORM\Column(nullable: true)]
    private ?int $shopNum = null;

    #[ORM\Column]
    private ?int $regionId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $orderDate = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $totalPrice = null;

    #[ORM\Column(length: 255)]
    private ?string $totalDiscountPrice = null;

    #[ORM\Column(length: 255)]
    private ?string $slicingCost = null;

    #[ORM\Column(length: 255)]
    private ?string $weight = null;

    #[ORM\Column]
    private ?int $totalBonus = null;

    #[ORM\Column]
    private ?bool $isDelivery = null;

    #[ORM\Column]
    private ?bool $isExpress = null;

    #[ORM\Column]
    private ?bool $hasAlcohol = null;

    #[ORM\Column(nullable: true)]
    private ?int $promoCodeId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getShopNum(): ?int
    {
        return $this->shopNum;
    }

    public function setShopNum(?int $shopNum): static
    {
        $this->shopNum = $shopNum;

        return $this;
    }

    public function getRegionId(): ?int
    {
        return $this->regionId;
    }

    public function setRegionId(int $regionId): static
    {
        $this->regionId = $regionId;

        return $this;
    }

    public function getOrderDate(): ?DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(DateTimeInterface $orderDate): static
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(string $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getTotalDiscountPrice(): ?string
    {
        return $this->totalDiscountPrice;
    }

    public function setTotalDiscountPrice(string $totalDiscountPrice): static
    {
        $this->totalDiscountPrice = $totalDiscountPrice;

        return $this;
    }

    public function getSlicingCost(): ?string
    {
        return $this->slicingCost;
    }

    public function setSlicingCost(string $slicingCost): static
    {
        $this->slicingCost = $slicingCost;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getTotalBonus(): ?int
    {
        return $this->totalBonus;
    }

    public function setTotalBonus(int $totalBonus): static
    {
        $this->totalBonus = $totalBonus;

        return $this;
    }

    public function isDelivery(): ?bool
    {
        return $this->isDelivery;
    }

    public function setDelivery(bool $isDelivery): static
    {
        $this->isDelivery = $isDelivery;

        return $this;
    }

    public function isExpress(): ?bool
    {
        return $this->isExpress;
    }

    public function setExpress(bool $isExpress): static
    {
        $this->isExpress = $isExpress;

        return $this;
    }

    public function hasAlcohol(): ?bool
    {
        return $this->hasAlcohol;
    }

    public function setHasAlcohol(bool $hasAlcohol): static
    {
        $this->hasAlcohol = $hasAlcohol;

        return $this;
    }

    public function getPromoCodeId(): ?int
    {
        return $this->promoCodeId;
    }

    public function setPromoCodeId(?int $promoCodeId): static
    {
        $this->promoCodeId = $promoCodeId;

        return $this;
    }
}
