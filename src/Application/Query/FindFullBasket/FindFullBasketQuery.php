<?php

declare(strict_types=1);

namespace App\Application\Query\FindFullBasket;

use App\Application\Query\QueryInterface;

readonly class FindFullBasketQuery implements QueryInterface
{
    public function __construct(
        public int $userId,
    ) {
    }
}
