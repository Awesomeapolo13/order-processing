<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controller;

use App\Application\UseCase\HealthCheckUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/health-check', methods: ['GET'])]
class HealthCheckController extends AbstractController
{
    public function __construct(
        private readonly HealthCheckUseCase $healthCheckUseCase,
    ) {
    }

    public function __invoke(): Response
    {
        $result = ($this->healthCheckUseCase)();

        return new JsonResponse($result);
    }
}
