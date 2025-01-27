<?php

declare(strict_types=1);

namespace App\Application\Api\Product;

use App\Domain\ValueObject\Region;

readonly class FindProductsDTO
{
    /**
     * @param string[] $supCodes
     */
    public function __construct(
        public int $shopNumber,
        public Region $region,
        public array $supCodes,
    ) {
    }
}
