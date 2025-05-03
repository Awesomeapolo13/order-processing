<?php

declare(strict_types=1);

namespace App\Application\Api\Shop\Exception;

class InvalidShopDataException extends \RuntimeException
{
    public function __construct(int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('Invalid data to create a shop', $code, $previous);
    }
}
