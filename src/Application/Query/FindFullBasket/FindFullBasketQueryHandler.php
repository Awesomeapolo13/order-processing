<?php

declare(strict_types=1);

namespace App\Application\Query\FindFullBasket;

use App\Application\Query\QueryHandlerInterface;
use App\Domain\Entity\Basket;
use App\Domain\Repository\BasketRepositoryInterface;

class FindFullBasketQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly BasketRepositoryInterface $basketRepository,
    ) {
    }

    public function __invoke(FindFullBasketQuery $query): ?Basket
    {
        return $this->basketRepository->findActiveBasketByUserId($query->userId);
    }
}
