<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\Request\UpdateProductQuantityRequest;
use App\Application\UseCase\UpdateProductQuantityUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/v1/basket/update', name: 'app.basket.update')]
final class UpdateBasketController extends AbstractController
{
    public function __construct(
        private readonly UpdateProductQuantityUseCase $updateProductQuantityUseCase,
    ) {
    }

    /**
     * @throws \Throwable
     */
    public function __invoke(
        #[MapRequestPayload]
        UpdateProductQuantityRequest $request,
    ): Response {
        return $this->json(['basket' => ($this->updateProductQuantityUseCase)($request)]);
    }
}
