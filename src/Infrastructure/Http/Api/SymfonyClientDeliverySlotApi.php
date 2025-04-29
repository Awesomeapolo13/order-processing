<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Api;

use App\Application\Api\DeliverySlot\DeliverySlotApiInterface;
use App\Application\Api\DeliverySlot\Exception\InvalidDeliverySlotDataException;
use App\Application\Api\DeliverySlot\FindDeliverySlotDTO;
use App\Domain\ValueObject\DeliverySlot;
use App\Infrastructure\Http\Client\SymfonyHttpClient;

class SymfonyClientDeliverySlotApi extends SymfonyHttpClient implements DeliverySlotApiInterface
{
    private const string FIND_SLOT_API_ENDPOINT = '/slot';

    public function findSlot(FindDeliverySlotDTO $dto): ?DeliverySlot
    {
        $data = [
            'shopNumber' => $dto->slotNumber,
            'regionCode' => $dto->regionCode,
        ];
        $result = $this->sendRequest(self::FIND_SLOT_API_ENDPOINT, self::METHOD_GET, $data)['data'];

        if ($result === []) {
            return null;
        }

        if (!isset($result['slotNumber']) || !is_int($result['slotNumber'])) {
            throw new InvalidDeliverySlotDataException();
        }

        return new DeliverySlot($result['slotNumber']);
    }
}
