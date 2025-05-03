<?php

declare(strict_types=1);

namespace App\Application\Command\CreateBasket;

use App\Application\Command\CommandHandlerInterface;
use App\Domain\Factory\BasketFactory;
use App\Domain\Repository\BasketRepositoryInterface;

class CreateBasketCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly BasketFactory $basketFactory,
        private readonly BasketRepositoryInterface $basketRepository,
    ) {
    }

    /**
     * @return int - basket ID
     */
    public function __invoke(CreateBasketCommand $command): int
    {
        $basket = $this->basketFactory->createDefaultAuthorized($command->userId, $command->region);
        $this->basketRepository->save($basket);

        return $basket->getId();
    }
}
