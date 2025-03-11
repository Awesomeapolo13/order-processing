<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Assembler\Response;

use App\Application\Assembler\Response\GetFullBasketResponseAssembler;
use App\Application\Assembler\ResponseAssemblerInterface;
use App\Application\Response\GetFullBasketResponse;
use App\Domain\Entity\Basket;
use App\Tests\Tools\TestDataSerializerTrait;
use JsonException;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

class GetFullBasketResponseAssemblerTest extends KernelTestCase
{
    private const string PROVIDER_FILE_NAME = 'get_full_basket_unit_test_provider.json';

    private ResponseAssemblerInterface $responseAssembler;
    private SerializerInterface $serializer;

    use TestDataSerializerTrait;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->responseAssembler = self::getContainer()->get(GetFullBasketResponseAssembler::class);
        $this->serializer = self::getContainer()->get('serializer');
    }


    /**
     * @throws JsonException
     * @throws ReflectionException
     */
    public function testResponseAccordingEntity(): void
    {

        $testData = $this->deserializeJSONTestData(
            __DIR__ . '/data/' . self::PROVIDER_FILE_NAME,
            Basket::class,
            GetFullBasketResponse::class,
            $this->serializer
        );

        foreach ($testData as $unitTestData) {
            [$input, $expectedResult] = $unitTestData;

            $result = $this->responseAssembler->createResponse($input);
            static::assertEquals($expectedResult, $result, 'Wrong assembling result');
        }
    }
}
