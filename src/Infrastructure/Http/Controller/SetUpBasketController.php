<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\Request\SetUpBasketRequest;
use App\Application\UseCase\SetUpBasketUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/v1/basket/setup', name: 'app.basket.add', methods: ['POST'])]
final class SetUpBasketController extends AbstractController
{
    public function __construct(
        private readonly SetUpBasketUseCase $setUpBasketUseCase,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] SetUpBasketRequest $request,
    ): Response {
        ($this->setUpBasketUseCase)($request);

        return $this->json([]);
    }
}
