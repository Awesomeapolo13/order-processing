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
        ?string $inputClassName,
        ?string $expectedClassName,
        SerializerInterface $serializer
    ): array {
        $result = [];
        $data = $this->extractJSON($dataFile);

        foreach ($data as $key => $testData) {
            $result[$key] = [
                isset($inputClassName) && class_exists($inputClassName) ? $serializer->denormalize($testData[0], $inputClassName) : $testData[0],
                isset($expectedClassName) && class_exists($expectedClassName) ? $serializer->denormalize($testData[1], $expectedClassName) : $testData[1],
            ];
        }

        return $result;
    }

    /**
     * @throws JsonException
     */
    public function deserializeSimpleJSON(string $dataFile): array
    {
        $result = [];
        $data = $this->extractJSON($dataFile);

        foreach ($data as $key => $testData) {
            $result[$key] = [
                $testData[0],
                $testData[1],
            ];
        }

        return $result;
    }

    /**
     * @throws JsonException
     */
    private function extractJSON(string $dataFile): array
    {
        $jsonContent = file_get_contents($dataFile);

        return (array)json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);
    }
}
