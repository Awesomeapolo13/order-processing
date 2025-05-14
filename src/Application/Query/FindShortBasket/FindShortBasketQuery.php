<?php

declare(strict_types=1);

namespace App\Application\Query\FindShortBasket;

use App\Application\Query\QueryInterface;

readonly class FindShortBasketQuery implements QueryInterface
{
    public function __construct(
        public int $userId,
        public int $regionCode,
    ) {
    }
}
