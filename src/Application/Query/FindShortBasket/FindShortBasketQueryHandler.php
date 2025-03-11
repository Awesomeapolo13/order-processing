<?php

declare(strict_types=1);

namespace App\Application\Query\FindShortBasket;

use App\Application\Assembler\ResponseAssemblerInterface;
use App\Application\Query\QueryHandlerInterface;
use App\Application\Response\ShortBasketResponse;
use App\Domain\Repository\BasketReadRepositoryInterface;
use App\Domain\ValueObject\Region;

class FindShortBasketQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly BasketReadRepositoryInterface $basketReadRepository,
        private readonly ResponseAssemblerInterface $shortBasketResponseAssembler,
    ) {
    }

    public function __invoke(FindShortBasketQuery $query): ?ShortBasketResponse
    {
        $shortBasket = $this->basketReadRepository->findShortBasket($query->userId, new Region($query->regionCode));

        return $shortBasket === null
            ? $this->shortBasketResponseAssembler->createResponse($shortBasket)
            : null;
    }
}
