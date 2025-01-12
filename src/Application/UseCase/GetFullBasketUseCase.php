<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Assembler\ResponseAssemblerInterface;
use App\Application\Command\CommandBusInterface;
use App\Application\Command\CreateBasket\CreateBasketCommand;
use App\Application\Command\UpdateBasket\UpdateBasketCommand;
use App\Application\Query\FindFullBasket\FindFullBasketQuery;
use App\Application\Query\QueryBusInterface;
use App\Application\Request\GetFullBasketRequest;
use App\Application\Response\GetFullBasketResponse;
use App\Domain\ValueObject\Region;
use Psr\Log\LoggerInterface;
use Throwable;

final class GetFullBasketUseCase
{
    public function __construct(
        private readonly ResponseAssemblerInterface $responseAssembler,
        private readonly LoggerInterface $logger,
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(GetFullBasketRequest $dto): GetFullBasketResponse
    {
        $userId = $dto->userId;
        $region = new Region($dto->regionCode);

        $basket = $this->queryBus->execute(new FindFullBasketQuery($userId));

        if ($basket === null) {
            $this->logger->info('No basket found, creating new', [
                'user_id' => $userId,
                'region' => $region->getRegionCode(),
            ]);

            $this->commandBus->execute(new CreateBasketCommand($userId, $region));
        }

        $this->commandBus->execute(new UpdateBasketCommand($userId, $region));
        $basket = $this->queryBus->execute(new FindFullBasketQuery($userId));

        return $this->responseAssembler->createResponse($basket);
    }
}
