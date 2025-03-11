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
use App\Domain\ValueObject\Weight;
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
            Price::zero(),
            Price::zero(),
            Price::zero(),
            5,
            null,
            1,
            null,
            null,
            new Weight('0.100'),
            true,
            true,
        );
    }

    public function findProducts(FindProductsDTO $DTO): array
    {
        return [];
    }
}
