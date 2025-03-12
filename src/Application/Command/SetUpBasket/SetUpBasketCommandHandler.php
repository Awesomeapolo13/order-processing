<?php

declare(strict_types=1);

namespace App\Application\Command\SetUpBasket;

use App\Application\Command\CommandHandlerInterface;

class SetUpBasketCommandHandler implements CommandHandlerInterface
{
    public function __invoke(SetUpBasketCommand $command): void
    {
        /**
         * @TODO:
         *  1) Реализовать бизнес метод аггрегата со всееми бизнес правилами
         *  2) Реализовать API для получения слота
         *  3) Реализовать API для получения магазина (или модуль)
         *  4) Составить логику определения, является ли корзина экспресс (через статический фабричный метод)
         */
    }
}
