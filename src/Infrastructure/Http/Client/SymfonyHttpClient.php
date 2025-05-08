<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Client;

use App\Infrastructure\Http\Client\Exception\HttpClientException;
use App\Infrastructure\Http\Client\Exception\HttpStatusException;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SymfonyHttpClient
{
    protected const string METHOD_GET = 'GET';
    protected const string METHOD_POST = 'POST';

    public function __construct(
        protected HttpClientInterface $httpClient,
        protected array $options = [],
        protected array $validStatuses = [],
    ) {
    }

    public function sendRequest(string $url, string $method, array $data = []): array
    {
        $data = $method === self::METHOD_GET ? ['query' => $data] : ['data' => $data];
        $data = array_merge($this->options, $data);
        try {
            $response = $this->getResponse($url, $method, $data);

            try {
                $result = $response->toArray(false);
            } catch (JsonException $exception) {
                $result = [$response->getContent(false)];
            }

            if (!in_array($response->getStatusCode(), $this->validStatuses, true)) {
                throw new HttpStatusException($response->getStatusCode(), $result['message'] ?? '', $result);
            }

            $result = [
                'headers' => $response->getHeaders(),
                'data' => $result,
            ];

            return $result;
        } catch (
            \Exception|
            TransportExceptionInterface|
            ClientExceptionInterface|
            DecodingExceptionInterface|
            RedirectionExceptionInterface|
            ServerExceptionInterface $exception
        ) {
            throw new HttpClientException(message: $exception->getMessage(), previous: $exception);
        }
    }

    /**
     * @throws TransportExceptionInterface
     */
    protected function getResponse(string $url, string $method, array $data = []): ResponseInterface
    {
        return $this->httpClient->request($method, $url, $data);
    }
}
