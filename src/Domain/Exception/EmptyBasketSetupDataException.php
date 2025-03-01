<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class EmptyBasketSetupDataException extends DomainException
{
    public function __construct(?array $context = null, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('Basket setup data has not been provided for this operation', $context, $code, $previous);
    }
}
