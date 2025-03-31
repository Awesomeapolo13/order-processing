<?php

declare(strict_types=1);

namespace Unit\Domain\Service;

use App\Domain\Service\DeliveryCostCalculator;
use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\Distance;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DeliveryCostCalculatorTest extends KernelTestCase
{
    private DeliveryCostCalculator $deliveryCostCalculator;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->deliveryCostCalculator = self::getContainer()->get(DeliveryCostCalculator::class);
    }

    #[DataProvider('provide')]
    public function testCostWithDiscountCalculation(Cost $totalDiscountCost, Distance $distance, Cost $expectedCost): void
    {
        $deliveryCost = $this->deliveryCostCalculator->calculateDeliveryCostWithDiscount($totalDiscountCost, $distance);

        self::assertSame(
            $expectedCost->getCost(),
            $deliveryCost->getCost(),
            'Delivery cost result ' . $deliveryCost->getCost() . ' does not equal the expected ' . $expectedCost->getCost()
        );
    }

    public static function provide(): array
    {
        return [
            'With default delivery cost' => [
                Cost::fromString('100.00'),
                Distance::create('20', false),
                Cost::fromString('200.00'),
            ],
            'Free delivery, cost equals to threshold' => [
                Cost::fromString('1500.00'),
                Distance::create('20', false),
                Cost::fromString('0.00'),
            ],
            'Free delivery, cost more than threshold' => [
                Cost::fromString('1501.00'),
                Distance::create('20', false),
                Cost::fromString('0.00'),
            ],
            'Cost less than threshold, with long duration and long distance' => [
                Cost::fromString('200.00'),
                Distance::create('60', true),
                Cost::fromString('1400.00'),
            ],
            'Cost less than threshold, with long duration' => [
                Cost::fromString('200.00'),
                Distance::create('20', true),
                Cost::fromString('400.00'),
            ],
            'Cost less than threshold, with long distance' => [
                Cost::fromString('200.00'),
                Distance::create('60', false),
                Cost::fromString('1200.00'),
            ],
            'Cost more than threshold, with long duration' => [
                Cost::fromString('1501.00'),
                Distance::create('20', true),
                Cost::fromString('200.00'),
            ],
            'Cost more than threshold, with long distance' => [
                Cost::fromString('1501.00'),
                Distance::create('60', false),
                Cost::fromString('1000.00'),
            ],
            'Cost more than threshold, with long duration and long distance' => [
                Cost::fromString('1501.00'),
                Distance::create('60', true),
                Cost::fromString('1200.00'),
            ],
        ];
    }
}
