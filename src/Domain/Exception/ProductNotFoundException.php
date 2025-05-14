<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class ProductNotFoundException extends \RuntimeException
{
    public function __construct(string $supCode, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('Product with sup-code ' . $supCode . ' not found', $code, $previous);
    }
}
