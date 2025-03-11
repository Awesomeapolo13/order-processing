<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Event\EventInterface;
use App\Domain\Event\ProductAddedToBasketFromCatalogEvent;
use App\Domain\Exception\EmptyBasketSetupDataException;
use App\Domain\Factory\BasketItemFactory;
use App\Domain\ValueObject\BasketType;
use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\ProductInterface;
use App\Domain\ValueObject\Region;
use App\Domain\ValueObject\Weight;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Basket
{
    private ?int $id = null;
    private int $version = 1;
    private ?DateTimeImmutable $deletedAt = null;
    private ?BasketDelivery $delivery = null;
    /**
     * @var Collection<BasketItem>
     */
    private Collection $basketItems;
    private Collection $domainEvents;

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
        $this->basketItems = new ArrayCollection();
        $this->domainEvents = new ArrayCollection();
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

    public function addProductFromCatalog(
        ProductInterface $product,
        ?int             $quantity,
        ?string          $weight,
        bool             $isPack,
        BasketItemFactory $basketItemFactory,
    ): void {
        if (
            $this->shopNum === null
            || $this->orderDate === null
            || ($this->type->isDelivery() && $this->delivery?->getSlot() === null)
        ) {
            throw new EmptyBasketSetupDataException([
                'id' => $this->id,
                'isDelivery' => $this->type->isDelivery(),
                'shopNum' => $this->shopNum,
                'orderDate' => $this->orderDate->format(DateTimeInterface::RFC3339),
            ]);
        }

        $supCode = $product->getSupCode();
        $isAlreadyExists = $this->basketItems->exists(
            function (BasketItem $basketItem) use ($supCode) {
                return $basketItem->getSupCode() === $supCode;
            }
        );

        if ($isAlreadyExists) {
            return;
        }

        $basketItem = $basketItemFactory->createByProductFromCatalog($product, $quantity, $weight, $isPack);
        $this->basketItems->add($basketItem);
        $basketItem->setBasket($this);
        $this->updateTimestamps();

        $this->domainEvents->add(
            new ProductAddedToBasketFromCatalogEvent(
                $this->userId,
                $this->region->getRegionCode(),
                $this->id,
                $basketItem->getId(),
                $basketItem->getSupCode(),
                $basketItem->getQuantity()->getQuantity(),
                $basketItem->getQuantity()->getWeight()->getWeight(),
                $basketItem->getQuantity()->isPack()
            )
        );
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

    /**
     * @return EventInterface[]
     */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents->toArray();
        $this->domainEvents->clear();

        return $events;
    }

    public function markAsDeleted(): void
    {
        $this->deletedAt = new DateTimeImmutable();
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    public function updateTimestamps(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    protected function recordEvent(EventInterface $event): void
    {
        $this->domainEvents->add($event);
    }
}
