<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use Throwable;

class BasketNotFoundException extends DomainException
{
    public function __construct(int $userId, ?array $context, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(sprintf('Basket for user with id %d not found', $userId), $context, $code, $previous);
    }
}
