<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class DomainException extends \RuntimeException
{
    private array $context = [];

    public function __construct(string $message = '', ?array $context = null, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if (isset($context)) {
            $this->context = $context;
        }
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
