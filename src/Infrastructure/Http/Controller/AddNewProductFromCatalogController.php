<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\Request\AddNewProductFromCatalogRequest;
use App\Application\UseCase\AddNewProductFromCatalogUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/v1/basket/catalog/add', name: 'app.basket.add')]
final class AddNewProductFromCatalogController extends AbstractController
{
    public function __construct(
        private readonly AddNewProductFromCatalogUseCase $addNewProductToBasketUseCase,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] AddNewProductFromCatalogRequest $request,
    ): Response {
        return $this->json(['basket' => ($this->addNewProductToBasketUseCase)($request)]);
    }
}
