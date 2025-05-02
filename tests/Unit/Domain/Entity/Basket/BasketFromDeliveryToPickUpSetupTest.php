<?php

declare(strict_types=1);

namespace Unit\Domain\Entity\Basket;

use App\Application\Api\Shop\Shop;
use App\Domain\Command\BasketSetUpDomainData;
use App\Domain\Entity\Basket;
use App\Domain\Entity\BasketDelivery;
use App\Domain\Event\BasketSettingsChangedEvent;
use App\Domain\Factory\BasketDeliveryFactory;
use App\Domain\ValueObject\BasketType;
use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\DeliverySlot;
use App\Domain\ValueObject\Distance;
use App\Domain\ValueObject\OrderDate;
use App\Domain\ValueObject\Region;
use App\Domain\ValueObject\Weight;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BasketFromDeliveryToPickUpSetupTest extends TestCase
{
    private Basket $basket;
    private BasketDeliveryFactory|MockObject $basketDeliveryFactory;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $orderDate = new DateTimeImmutable('now +5 hours');
        $createdAt = new DateTimeImmutable();
        $updatedAt = $createdAt;
        $slicingCost = Cost::zero();
        $totalCost = Cost::fromString('100.00');
        $totalDiscountCost = Cost::fromString('80.00');
        $weight = Weight::fromStringNullable('20.000');
        $slot = new DeliverySlot(11);
        $distance = Distance::create('20.000', false);

        $delivery = new BasketDelivery(
            slot: $slot,
            isFromUserShop: true,
            distance: $distance,
            deliveryCost: Cost::fromString('200.00'),
            deliveryDiscountCost: Cost::fromString('200.00')
        );
        $this->basket = new Basket(
            userId: 10,
            region: new Region(52),
            type: BasketType::create(true, false, $orderDate),
            orderDate: $orderDate,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            slicingCost: $slicingCost,
            totalCost: $totalCost,
            totalDiscountCost:  $totalDiscountCost,
            weight: $weight,
        );
        $this->basket->setDelivery($delivery);

        $this->basketDeliveryFactory = $this->createMock(BasketDeliveryFactory::class);
        parent::setUp();
    }

    public function testBasketPickUpSetUp(): void
    {
        $domainData = new BasketSetUpDomainData(
            isDelivery: false,
            orderDate: OrderDate::create(new DateTimeImmutable('now +6 hours')),
            shop: new Shop(
                shopNumber: 12,
                region: $this->basket->getRegion(),
                openDateTime: new DateTimeImmutable('8:00'),
                closeDateTime: new DateTimeImmutable('22:00')
            ),
            distance: null,
            deliverySlot: null,
            isFromUserShop:  true,
        );

        $basket = $this->basket;
        $this->basket->setUpBasket($domainData, $this->basketDeliveryFactory);

        $this::assertFalse($basket->getType()->isDelivery());
        $this::assertNotNull($basket->getShopNum());
        $this::assertNull($basket->getDelivery());
        $this::assertNotNull($basket->getUpdatedAt());

        $events = $basket->releaseEvents();
        $this::assertCount(1, $events);
        $this::assertInstanceOf(BasketSettingsChangedEvent::class, $events[0], 'Wrong domain event type');
    }
}
