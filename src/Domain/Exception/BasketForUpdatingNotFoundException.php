<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class BasketForUpdatingNotFoundException extends \RuntimeException
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct('No basket for updating state found', 0, $previous);
    }
}
