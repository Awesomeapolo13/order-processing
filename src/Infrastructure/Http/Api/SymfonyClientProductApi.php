<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Api;

use App\Application\Api\Product\FindProductDTO;
use App\Application\Api\Product\FindProductsDTO;
use App\Application\Api\Product\Product;
use App\Application\Api\Product\ProductApiInterface;
use App\Domain\Enum\ProductType;
use App\Domain\ValueObject\Price;
use App\Domain\ValueObject\ProductInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SymfonyClientProductApi implements ProductApiInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function findProduct(FindProductDTO $request): ProductInterface
    {
        // TODO: Implement findProduct() method.
        return new Product(
            '',
            ProductType::PIECE,
            new Price(),
            new Price(),
            5,
            null,
            1,
            null,
            null,
            true,
        );
    }

    public function findProducts(FindProductsDTO $DTO): array
    {
        return [];
    }
}
