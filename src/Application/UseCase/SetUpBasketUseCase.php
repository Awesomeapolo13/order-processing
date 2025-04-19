<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\SetUpBasket\SetUpBasketCommand;
use App\Application\Request\SetUpBasketRequest;

final class SetUpBasketUseCase
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function __invoke(SetUpBasketRequest $request): void
    {
        $this->commandBus->execute(
            new SetUpBasketCommand(
                $request->userId,
                $request->regionCode,
                new \DateTimeImmutable($request->orderDate),
                $request->isDelivery,
                $request->shopNumber,
                $request->slotNumber,
                $request->distance,
                $request->longDuration,
            )
        );
    }
}
