<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Api;

use App\Application\Api\Shop\FindShopDTO;
use App\Application\Api\Shop\ShopApiInterface;
use App\Application\Assembler\ShopAssembler;
use App\Domain\ValueObject\ShopInterface;
use App\Infrastructure\Http\Client\SymfonyHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SymfonyClientShopApi extends SymfonyHttpClient implements ShopApiInterface
{
    private const string FIND_SHOP_API_ENDPOINT = '/shop';

    public function __construct(
        private readonly ShopAssembler $shopAssembler,
        HttpClientInterface            $httpClient,
        array                          $options = [],
        array                          $validStatuses = []
    ) {
        parent::__construct($httpClient, $options, $validStatuses);
    }

    public function findShop(FindShopDTO $dto): ?ShopInterface
    {
       $data = [
           'shopNumber' => $dto->shopNumber,
           'regionCode' => $dto->regionCode,
       ];
       $result = $this->sendRequest(self::FIND_SHOP_API_ENDPOINT, self::METHOD_GET, $data)['data'];

       return $this->shopAssembler->createShopFromArray($result);
    }
}
