<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Client\Exception;

class HttpStatusException extends \RuntimeException
{
    private array $content = [];

    public function __construct(int $statusCode, string $errorMessage, array $content, int $code = 0, ?\Throwable $previous = null)
    {
        $this->content = $content;

        parent::__construct('Unacceptable HTTP status code ' . $statusCode . '. Message ' . $errorMessage, $code, $previous);
    }

    public function getContent(): array
    {
        return $this->content;
    }
}
