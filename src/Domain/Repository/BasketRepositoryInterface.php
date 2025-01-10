<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Basket;

interface BasketRepositoryInterface
{
    public function findActiveBasketByUserId(int $userId): ?Basket;
    public function setBasketDeleted(int $userId): void;

    public function save(Basket $basket): void;
}
