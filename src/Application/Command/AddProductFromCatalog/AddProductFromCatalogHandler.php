<?php

declare(strict_types=1);

namespace App\Application\Command\AddProductFromCatalog;

use App\Application\Api\Product\FindProductDTO;
use App\Application\Api\Product\ProductApiInterface;
use App\Application\Command\CommandHandlerInterface;
use App\Application\Event\EventBusInterface;
use App\Domain\Event\ProductAddedToBasketFromCatalogEvent;
use App\Domain\Exception\BasketNotFoundException;
use App\Domain\Exception\ProductNotFoundException;
use App\Domain\Factory\BasketItemFactory;
use App\Domain\Repository\BasketRepositoryInterface;

class AddProductFromCatalogHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly BasketRepositoryInterface $basketRepository,
        private readonly ProductApiInterface $productApi,
        private readonly BasketItemFactory $basketItemFactory,
    ) {
    }

    public function __invoke(AddProductFromCatalogCommand $command): void
    {
        $basket = $this->basketRepository->findActiveBasketByUserId($command->userId);
        if ($basket === null) {
            throw new BasketNotFoundException($command->userId, [
                'userId' => $command->userId,
                'region' => $command->region->getRegionCode(),
            ]);
        }

        $product = $this->productApi->findProduct(
            new FindProductDTO(
                shopNumber: $basket->getShopNum(),
                region: $basket->getRegion(),
                supCode: $command->supCode,
            )
        );

        if ($product === null) {
            throw new ProductNotFoundException($command->supCode);
        }

        $basketItem = $this->basketItemFactory->createByProductFromCatalog($product, $command->quantity, $command->weight, $command->isPack);
        $basket->addBasketItem($basketItem);
        $basket->updateTimestamps();
        $this->basketRepository->save($basket);
    }
}
