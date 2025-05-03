<?php

declare(strict_types=1);

namespace App\Application\Api\Shop;

readonly class FindShopDTO
{
    public function __construct(
        public int $shopNumber,
        public string $regionCode
    ) {
    }
}
