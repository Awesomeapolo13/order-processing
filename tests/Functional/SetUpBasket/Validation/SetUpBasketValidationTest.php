<?php

declare(strict_types=1);

namespace Functional\SetUpBasket\Validation;

use App\Tests\Tools\TestDataSerializerTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class SetUpBasketValidationTest extends WebTestCase
{
    private const string HTTP_METHOD = 'POST';
    private const string URL = '/api/v1/basket/setup';
    private const string PROVIDER_FILE_NAME = 'set_up_basket_request_validation_test_provider.json';
    protected AbstractBrowser $client;
    private SerializerInterface $serializer;

    use TestDataSerializerTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->serializer = self::getContainer()->get('serializer');
    }

    /**
     * @throws \ReflectionException
     * @throws \JsonException
     */
    public function testValidationResponses(): void
    {
        $testData = $this->deserializeJSONTestData(
            __DIR__ . '/data/' . self::PROVIDER_FILE_NAME,
            null,
            null,
            $this->serializer
        );

        foreach ($testData as $key => $responseTestData) {
            [$input, $expectedResult] = $responseTestData;
            $futureOrderDate = (new \DateTime())->modify('+1 day')->format('Y-m-d\TH:i:sP');
            $input['orderDate'] = $futureOrderDate;

            $response = $this->sendRequestAndGetResponse(self::HTTP_METHOD, self::URL, $input);

            self::assertResponseStatusCodeSame(
                Response::HTTP_BAD_REQUEST,
                'Expected HTTP response code 400 got ' . $response->getStatusCode() . '. ' . $key
            );
            self::assertSame($expectedResult, json_decode($response->getContent(), true), $key);
        }
    }

    #[DataProvider('orderDateValidationProvider')]
    public function testOrderDateParameter($requestBody, $expectedResponse): void
    {
        $response = $this->sendRequestAndGetResponse(self::HTTP_METHOD, self::URL, $requestBody);
        self::assertResponseStatusCodeSame(
            Response::HTTP_BAD_REQUEST,
            'Expected HTTP response code 400 got ' . $response->getStatusCode()
        );
        self::assertSame($expectedResponse, json_decode($response->getContent(), true));
    }

    protected function sendRequestAndGetResponse(string $method, string $url, ?array $body = null): Response
    {
        $this->client->request($method, $url, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($body));

        return $this->client->getResponse();
    }

    /**
     * @throws \DateMalformedStringException
     */
    public static function orderDateValidationProvider(): array
    {
        $pastDate = (new \DateTime())->modify('-1 day')->format('Y-m-d\TH:i:sP');

        return [
            'Empty orderDate' => [
                [
                    'regionCode' => 77,
                    'userId' => 3,
                    'isDelivery' => false,
                    'shopNumber' => 3,
                ],
                [
                    'title' => 'Incorrect request data',
                    'errors' => [
                        [
                            'name' => 'orderDate',
                            'message' => 'This value should be of type string.',
                        ],
                    ],
                ],
            ],
            'Wrong orderDate type' => [
                [
                    'regionCode' => 77,
                    'userId' => 3,
                    'orderDate' => 777,
                    'isDelivery' => false,
                    'shopNumber' => 3,
                ],
                [
                    'title' => 'Incorrect request data',
                    'errors' => [
                        [
                            'name' => 'orderDate',
                            'message' => 'This value should be of type string.',
                        ],
                    ],
                ],
            ],
            'Wrong orderDate format' => [
                [
                    'regionCode' => 77,
                    'userId' => 3,
                    'orderDate' => (new \DateTime())->modify('+1 day')->format('Y-m-d'),
                    'isDelivery' => false,
                    'shopNumber' => 3,
                ],
                [
                    'title' => 'Incorrect request data',
                    'errors' => [
                        [
                            'name' => 'orderDate',
                            'message' => 'Incorrect date time format.',
                        ],
                    ],
                ],
            ],
            'OrderDate in the past' => [
                [
                    'regionCode' => 77,
                    'userId' => 3,
                    'orderDate' => $pastDate,
                    'isDelivery' => false,
                    'shopNumber' => 3,
                ],
                [
                    'title' => 'Incorrect request data',
                    'errors' => [
                        [
                            'name' => 'orderDate',
                            'message' => 'Order date can not be in the past. Got "' . $pastDate . '".',
                        ],
                    ],
                ],
            ],
        ];
    }
}
