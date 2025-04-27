<?php

declare(strict_types=1);

namespace App\Application\Api\Shop;

use App\Domain\ValueObject\ShopInterface;

interface ShopApiInterface
{
    public function findShop(FindShopDTO $dto): ?ShopInterface;
}
