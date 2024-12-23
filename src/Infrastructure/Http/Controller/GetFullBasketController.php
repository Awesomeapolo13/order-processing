<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\UseCase\GetFullBasketUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/v1/basket', name: 'app.basket.full')]
class GetFullBasketController extends AbstractController
{
    public function __construct(
        private readonly GetFullBasketUseCase $getFullBasketUseCase,
    ) {
    }

    public function __invoke(): Response
    {
        return $this->json(($this->getFullBasketUseCase)());
    }
}
