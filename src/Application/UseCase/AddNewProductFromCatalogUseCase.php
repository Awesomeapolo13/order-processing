<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Command\AddProductFromCatalog\AddProductFromCatalogCommand;
use App\Application\Command\CommandBusInterface;
use App\Application\Query\FindShortBasket\FindShortBasketQuery;
use App\Application\Query\QueryBusInterface;
use App\Application\Request\AddNewProductFromCatalogRequest;
use App\Application\Response\ShortBasketResponse;
use App\Domain\Exception\BasketNotFoundException;
use App\Domain\ValueObject\Region;
use Psr\Log\LoggerInterface;

final class AddNewProductFromCatalogUseCase
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
        private readonly LoggerInterface $logger,
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

        $shortBasket = $this->queryBus->execute(
            new FindShortBasketQuery(
                $request->userId,
                $request->regionCode,
            )
        );

        if ($shortBasket === null) {
            $this->logger->error('Tried to add product without basket', [
                'userId' => $request->userId,
                'regionCode' => $request->regionCode,
                'supCode' => $request->supCode,
            ]);

            throw new BasketNotFoundException($request->userId, [
                'userId' => $request->userId,
                'regionCode' => $request->regionCode,
            ]);
        }

        return $shortBasket;
    }
}
