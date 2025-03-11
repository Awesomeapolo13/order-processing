<?php

declare(strict_types=1);

namespace App\Application\Api\Product;

use App\Domain\ValueObject\ProductInterface;

interface ProductApiInterface
{
    public function findProduct(FindProductDTO $request): ?ProductInterface;

    /**
     * @return ProductInterface[]
     */
    public function findProducts(FindProductsDTO $DTO): array;
}
