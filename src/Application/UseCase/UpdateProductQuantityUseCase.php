<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\UpdateProductQuantity\UpdateProductQuantityCommand;
use App\Application\Request\UpdateProductQuantityRequest;

final class UpdateProductQuantityUseCase
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(UpdateProductQuantityRequest $request): void
    {
        $this->commandBus->execute(
            new UpdateProductQuantityCommand(
                userId: $request->userId,
                regionCode: $request->regionCode,
                supCode: $request->supCode,
                quantity: $request->quantity,
                weight: $request->weight,
                isPack: $request->isPack,
            ),
        );
    }
}
