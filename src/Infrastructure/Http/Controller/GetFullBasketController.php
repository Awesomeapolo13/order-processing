<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\Request\GetFullBasketRequest;
use App\Application\UseCase\GetFullBasketUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

#[Route(path: '/api/v1/basket', name: 'app.basket.full')]
final class GetFullBasketController extends AbstractController
{
    public function __construct(
        private readonly GetFullBasketUseCase $getFullBasketUseCase,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(
        #[MapQueryString] GetFullBasketRequest $request,
    ): Response {
        return $this->json(['basket' => ($this->getFullBasketUseCase)($request)]);
    }
}
