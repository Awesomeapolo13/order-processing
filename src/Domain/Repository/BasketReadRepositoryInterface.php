<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\ReadModel\ShortBasketReadModelInterface;
use App\Domain\ValueObject\Region;

interface BasketReadRepositoryInterface
{
    public function findShortBasket(int $userId, Region $region): ?ShortBasketReadModelInterface;
}
