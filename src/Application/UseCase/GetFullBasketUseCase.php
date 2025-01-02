<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Assembler\ResponseAssemblerInterface;
use App\Application\Request\GetFullBasketRequest;
use App\Application\Response\GetFullBasketResponse;
use App\Domain\Entity\Basket;
use App\Domain\Repository\BasketRepositoryInterface;
use App\Domain\ValueObject\BasketType;
use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\Region;
use App\Domain\ValueObject\Weight;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

final class GetFullBasketUseCase
{
    public function __construct(
        private readonly BasketRepositoryInterface $basketRepository,
        private readonly ResponseAssemblerInterface $responseAssembler,
    ) {
    }

    public function __invoke(GetFullBasketRequest $dto): GetFullBasketResponse
    {
        $region = new Region($dto->regionCode);
        try {
            $basket = $this->basketRepository->findBasketByUserId($dto->userId);
        } catch (NoResultException $exception) {
            // ToDo: Logg the error
            $basket = new Basket(
                new Region($dto->regionCode),
                new BasketType(false, true, false),
                new DateTime(),
                new DateTimeImmutable(),
                new DateTimeImmutable(),
                new Cost('0.00'),
                new Cost('0.00'),
                new Cost('0.00'),
                new Weight('0.000'),
                0,
                $dto->userId,
                null,
            );
        } catch (NonUniqueResultException $exception) {
            // ToDo: Logg the error
            // ToDo: Handle a double of the basket
        }

        if (!$basket->getRegion()->isSame($region)) {
            // ToDo: Delete a Basket and create a new one

            // ToDo: Задать вопрос клауду, как лучше сделать,
            //  удалить корзину сразу или же помечать корзины их на удаление и сносить кроной.
        }

        return $this->responseAssembler->createResponse($basket);
    }
}
