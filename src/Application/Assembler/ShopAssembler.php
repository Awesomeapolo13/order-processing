<?php

declare(strict_types=1);

namespace App\Application\Assembler;

use App\Application\Api\Shop\Exception\InvalidShopDataException;
use App\Application\Api\Shop\Shop;
use App\Domain\ValueObject\Region;
use App\Domain\ValueObject\ShopInterface;

class ShopAssembler
{
    public function createShopFromArray(array $data): ShopInterface
    {
        if (!isset($data['shopNumber'], $data['region'], $data['openTime'], $data['closeTime'])) {
            throw new InvalidShopDataException();
        }

        try {
            return new Shop(
                (int) $data['shopNumber'],
                new Region($data['region']),
                new \DateTimeImmutable($data['openTime']),
                new \DateTimeImmutable($data['closeTime'])
            );
        } catch (\DateMalformedStringException $exception) {
            throw new InvalidShopDataException();
        }
    }
}
