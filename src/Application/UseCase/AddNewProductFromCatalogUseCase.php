<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Request\AddNewProductFromCatalogRequest;
use App\Application\Response\ShortBasketResponse;
use DateTime;

final class AddNewProductFromCatalogUseCase
{
    public function __invoke(AddNewProductFromCatalogRequest $request): ShortBasketResponse
    {
        return new ShortBasketResponse(
            id: 1,
            isExpress: true,
            isDelivery: false,
            orderDate: new DateTime(),
            totalDiscountCost: '0.00',
            basketItems: []
        );
    }
}
