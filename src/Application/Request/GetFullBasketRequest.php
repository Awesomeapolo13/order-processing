<?php

declare(strict_types=1);

namespace App\Application\Request;

readonly class GetFullBasketRequest
{
    public function __construct(
        public int $userId,
        public int $regionCode,
    ) {
    }
}
