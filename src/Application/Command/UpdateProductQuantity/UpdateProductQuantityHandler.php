<?php

declare(strict_types=1);

namespace App\Application\Command\UpdateProductQuantity;

use App\Application\Command\CommandHandlerInterface;

class UpdateProductQuantityHandler implements CommandHandlerInterface
{
    public function __invoke(UpdateProductQuantityCommand $command): void
    {
        /*
         * ToDo
         *  1) Получаем корзину и блокируем на изменения
         *  2) Проверяем что корзина есть, если нет то ошибка (см. добавление продукта)
         *  3) Проверяем что продукт есть в корзине. Если нет,то ошибка.
         *  4) Добавляем новый доменный метод. Обновляем количество товара.
         */
    }
}
