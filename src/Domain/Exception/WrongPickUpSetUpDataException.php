<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Domain\Exception\DomainException;

class WrongPickUpSetUpDataException extends DomainException
{
    public function __construct(?array $context = null, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('Invalid data for basket setup: no shop number', $context, $code, $previous);
    }
}
