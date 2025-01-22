<?php

declare(strict_types=1);

namespace App\Application\UseCase;

final class HealthCheckUseCase
{
    public function __invoke(): array
    {
        return ['status' => 'OK'];
    }
}
