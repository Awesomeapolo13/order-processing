<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Basket;

interface BasketRepositoryInterface
{
    public function findBasketByUserId(int $userId): Basket;
}
