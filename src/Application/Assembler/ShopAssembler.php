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
        if (!isset($data['shopNumber'], $data['regionCode'], $data['openDateTime'], $data['closeDateTime'])) {
            throw new InvalidShopDataException();
        }

        try {
            return new Shop(
                (int)$data['shopNumber'],
                new Region($data['regionCode']),
                new \DateTimeImmutable($data['openDateTime']),
                new \DateTimeImmutable($data['closeDateTime'])
            );
        } catch (\DateMalformedStringException $exception) {
            throw new InvalidShopDataException();
        }
    }
}
