<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use RuntimeException;
use Throwable;

class BasketNotFoundException extends RuntimeException
{
    public function __construct(int $userId, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(sprintf('Basket for user with id %d not found', $userId), $code, $previous);
    }
}
