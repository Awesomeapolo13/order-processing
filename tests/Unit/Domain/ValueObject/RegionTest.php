<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\Enum\RegionCodeEnum;
use App\Domain\ValueObject\Region;
use App\Tests\Tools\TestDataSerializerTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

class RegionTest extends KernelTestCase
{
    use TestDataSerializerTrait;
    private const string CREATE_REGION_PROVIDER_FILE_NAME = 'create_region_test_provider.json';
    private const string COMPARE_REGION_PROVIDER_FILE_NAME = 'compare_region_test_provider.json';
    private const int NON_EXISTED_REGION_CODE = 8888;

    private SerializerInterface $serializer;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->serializer = self::getContainer()->get('serializer');
    }

    /**
     * @throws \JsonException
     */
    public function testRegionCreating(): void
    {
        $testData = $this->deserializeSimpleJSON(
            __DIR__ . '/data/' . self::CREATE_REGION_PROVIDER_FILE_NAME,
        );

        foreach ($testData as $test) {
            [$input, $expectedResult] = $test;

            $region = new Region($input);
            $result = $region->getRegionCode();
            static::assertSame(
                $result,
                $expectedResult,
                'Expected region code is ' . $expectedResult . '. Got ' . $result,
            );
        }
    }

    public function testWrongRegionCode(): void
    {
        $unsupportedRegion = self::NON_EXISTED_REGION_CODE;
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported region ' . $unsupportedRegion);

        new Region($unsupportedRegion);
    }

    /**
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function testRegionComparing(): void
    {
        $testData = $this->deserializeJSONTestData(
            __DIR__ . '/data/' . self::COMPARE_REGION_PROVIDER_FILE_NAME,
            Region::class,
            null,
            $this->serializer,
        );

        foreach ($testData as $test) {
            [$input, $expectedResult] = $test;

            $region = new Region(RegionCodeEnum::NIZHNY_NOVGOROD->value);
            $result = $input->isSame($region);
            static::assertSame(
                $result,
                $expectedResult,
                'Expected regions are same as ' . $expectedResult ? 'true' : 'false. Got ' . $result,
            );
        }
    }
}
