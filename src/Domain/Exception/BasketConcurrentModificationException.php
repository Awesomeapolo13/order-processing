<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class BasketConcurrentModificationException extends \RuntimeException
{
    public function __construct(int $userId, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(
            sprintf(
                'Basket for user %d was modified concurrently. Please try again.',
                $userId
            ),
            $code,
            $previous,
        );
    }
}
