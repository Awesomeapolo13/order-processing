<?php

declare(strict_types=1);

namespace App\Application\Api\Shop;

use App\Domain\ValueObject\Region;
use App\Domain\ValueObject\ShopInterface;

readonly class Shop implements ShopInterface
{
    public function __construct(
        private int $shopNumber,
        private Region $region,
        private \DateTimeImmutable $openDateTime,
        private \DateTimeImmutable $closeDateTime
    ) {
    }

    public function getNumber(): int
    {
        return $this->shopNumber;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function getOpenDateTime(): ?\DateTimeImmutable
    {
        return $this->openDateTime;
    }

    public function getCloseDateTime(): ?\DateTimeImmutable
    {
        return $this->closeDateTime;
    }
}
