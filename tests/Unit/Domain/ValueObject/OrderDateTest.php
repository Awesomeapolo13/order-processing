<?php

declare(strict_types=1);

namespace Unit\Domain\ValueObject;

use App\Domain\ValueObject\OrderDate;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderDateTest extends KernelTestCase
{
    /**
     * @throws \DateMalformedStringException
     */
    #[DataProvider('correctOrderDateProvider')]
    public function testCorrectCreateOrderDate(string $orderDate, string $currentDate): void
    {
        $orderDate = OrderDate::create(new \DateTimeImmutable($orderDate));

        static::assertLessThan(
            $orderDate->getOrderDate(),
            new \DateTimeImmutable($currentDate),
            'Order date can not be in the past. Order date: ' . $orderDate->getOrderDate()->format(\DateTimeInterface::RFC3339) . ', current date: ' . $currentDate,
        );
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[DataProvider('wrongOrderDateProvider')]
    public function testWrongCreateOrderDate(string $orderDate): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Order date cannot be before current date ' . $orderDate);

        OrderDate::create(new \DateTimeImmutable($orderDate));
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function correctOrderDateProvider(): array
    {
        $orderDate = new \DateTime();
        $currentDate = clone $orderDate;

        return [
            'Tomorrow at the same time' => [
                (clone $orderDate)->modify('+1 day')?->format(\DateTimeInterface::RFC3339),
                $currentDate->format(\DateTimeInterface::RFC3339),
            ],
            'Today after two hours' => [
                (clone $orderDate)->modify('+2 hours')?->format(\DateTimeInterface::RFC3339),
                $currentDate->format(\DateTimeInterface::RFC3339),
            ],
        ];
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function wrongOrderDateProvider(): array
    {
        $orderDate = new \DateTime();

        return [
            'Today at the same time' => [
                (clone $orderDate)?->format(\DateTimeInterface::RFC3339),
            ],
            'Today after two hours' => [
                (clone $orderDate)->modify('-2 hours')?->format(\DateTimeInterface::RFC3339),
            ],
            'Yesterday at the same time' => [
                (clone $orderDate)->modify('-1 day')?->format(\DateTimeInterface::RFC3339),
            ],
        ];
    }
}
