<?php

declare(strict_types=1);

namespace Unit\Domain\ValueObject;

use App\Domain\ValueObject\Cost;
use App\Tests\Tools\TestDataSerializerTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * ToDo: Add tests for operations between costs.
 */
class CostTest extends KernelTestCase
{
    use TestDataSerializerTrait;
    private const string CREATE_SUCCESS_COST_FROM_CONSTRUCTOR_PROVIDER_FILE_NAME = 'create_success_cost_from_constructor_test_provider.json';
    private const string CREATE_SUCCESS_COST_FROM_STRING_PROVIDER_FILE_NAME = 'create_success_cost_from_string.json';
    private const string ZERO_COST_VALUE = '0.00';
    private const array WRONG_CONSTRUCTOR_INPUT_DATA = ['', '.0', '.00', '0.0', 'words', 'w.00', '0.w', '0.ww', 'w.ww'];

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    public function testSuccessCostCreatingFromConstructor(): void
    {
        $testData = $this->deserializeSimpleJSON(
            __DIR__ . '/data/' . self::CREATE_SUCCESS_COST_FROM_CONSTRUCTOR_PROVIDER_FILE_NAME,
        );

        foreach ($testData as $test) {
            [$input, $expectedResult] = $test;

            $cost = new Cost($input);
            $result = $cost->getCost();
            static::assertSame(
                $result,
                $expectedResult,
                'Expected cost is ' . $expectedResult . '. Got ' . $result,
            );
        }
    }

    public function testWrongCostCreationFromConstructor(): void
    {
        foreach (self::WRONG_CONSTRUCTOR_INPUT_DATA as $value) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Cost must be in format "0.00", got: ' . $value);

            new Cost($value);
        }
    }

    public function testZeroCostCreating(): void
    {
        $cost = Cost::zero();

        static::assertSame($cost->getCost(), self::ZERO_COST_VALUE, 'Cost should be zero, got ' . $cost->getCost());
    }

    public function testCostCreatingFromString(): void
    {
        $testData = $this->deserializeSimpleJSON(
            __DIR__ . '/data/' . self::CREATE_SUCCESS_COST_FROM_STRING_PROVIDER_FILE_NAME,
        );

        foreach ($testData as $test) {
            [$input, $expectedResult] = $test;

            $cost = Cost::fromString($input);
            $result = $cost->getCost();
            static::assertSame(
                $result,
                $expectedResult,
                'Expected cost is ' . $expectedResult . '. Got ' . $result,
            );
        }
    }

    #[DataProvider('fromStrongWithWrongFormat')]
    public function testCostCreatingFromStringWithWrongFormat($testData, $expectedResult): void
    {
        $cost = Cost::fromString($testData);
        $result = $cost->getCost();
        static::assertSame(
            $result,
            $expectedResult,
            'Expected cost is ' . $expectedResult . '. Got ' . $result,
        );
    }

    public static function fromStrongWithWrongFormat(): array
    {
        return [
            'Correct from wrong format (point as first character)' => [
                '.0',
                '0.00',
            ],
            'Correct from wrong formated string (point as first character)' => [
                '.63',
                '0.63',
            ],
        ];
    }
}
