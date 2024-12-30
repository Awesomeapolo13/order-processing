<?php

declare(strict_types=1);

namespace App\Tests\Tools;

use JsonException;
use ReflectionException;
use Symfony\Component\Serializer\SerializerInterface;

trait TestDataSerializerTrait
{

    /**
     * @throws ReflectionException
     * @throws JsonException
     */
    private function deserializeJSONTestData(
        string $dataFile,
        string $inputClassName,
        string $expectedClassName,
        SerializerInterface $serializer
    ): array {
        $result = [];
        $jsonContent = file_get_contents($dataFile);
        $data = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);

        foreach ($data as $key => $unitTestData) {
            $result[$key] = [
                $serializer->denormalize($unitTestData[0], $inputClassName),
                $serializer->denormalize($unitTestData[1], $expectedClassName)
            ];
        }

        return $result;
    }
}
