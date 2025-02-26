<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Command\AddProductFromCatalog\AddProductFromCatalogCommand;
use App\Application\Command\CommandBusInterface;
use App\Application\Query\FindShortBasket\FindShortBasketQuery;
use App\Application\Query\QueryBusInterface;
use App\Application\Request\AddNewProductFromCatalogRequest;
use App\Application\Response\ShortBasketResponse;
use App\Domain\ValueObject\Region;

final class AddNewProductFromCatalogUseCase
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function __invoke(AddNewProductFromCatalogRequest $request): ShortBasketResponse
    {
        $this->commandBus->execute(
            new AddProductFromCatalogCommand(
                $request->userId,
                new Region($request->regionCode),
                $request->supCode,
                (int)$request->quantity,
                $request->weight,
                $request->isPack,
            )
        );

        return $this->queryBus->execute(
            new FindShortBasketQuery(
                $request->userId,
                $request->regionCode,
            )
        );
    }
}
