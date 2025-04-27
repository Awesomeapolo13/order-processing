<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Client\Exception;

class HttpClientException extends \RuntimeException
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct('Unexpected http client exception: ' . $message, $code, $previous);
    }
}
