<?php

declare(strict_types=1);

namespace App\Application\Event\Handler;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\UpdateBasket\UpdateBasketCommand;
use App\Application\Event\EventHandlerInterface;
use App\Domain\Event\ProductAddedToBasketFromCatalogEvent;
use App\Domain\ValueObject\Region;

class ProductAddedToBasketFromCatalogEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(ProductAddedToBasketFromCatalogEvent $event): void
    {
        $this->commandBus->execute(
            new UpdateBasketCommand(
                userId: $event->userId,
                region: new Region($event->region)
            )
        );
    }
}
