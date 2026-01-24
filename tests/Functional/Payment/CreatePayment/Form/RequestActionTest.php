<?php

namespace Test\Functional\Payment\CreatePayment\Form;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Ramsey\Uuid\Uuid;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{
    use ArraySubsetAsserts;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([
           RequestFixture::class,
        ]);
    }
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/process-payment', [
            'email' => 'test@app.ru',
            'sourcePaymentId' => 'b38e76c0-ac23-4c48-85fd-975f32c8801f',
            'paymentType' => 'form'
        ]));

        $this->assertEquals(201, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);
        self::assertArraySubset([
            'amount' => 350,
            'currency' => 'RUB',
        ], $data);
    }
    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/process-payment'));

        $this->assertEquals(422, $response->getStatusCode());

        $body = (string)$response->getBody();
        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'email' => 'This value should not be blank.',
                'sourcePaymentId' => 'This value should not be blank.',
                'paymentType' => 'The value you selected is not a valid choice.'
            ]
        ], $data);
    }
    public function testInvalidPaymentType(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/process-payment', [
            'email' => 'test@app.ru',
            'sourcePaymentId' => Uuid::uuid4()->toString(),
            'paymentType' => 'invalid'
        ]));

        self::assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset([
            'errors' => [
                'paymentType' => 'The value you selected is not a valid choice.'
            ],
        ], $data);
    }
    public function testNotFound(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/process-payment', [
            'email' => 'test@app.ru',
            'sourcePaymentId' => Uuid::uuid4()->toString(),
            'paymentType' => 'form'
        ]));

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset([
            'message' => 'Product not found.',
        ], $data);
    }

    public function testInvalidEmail(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/process-payment', [
            'email' => 'invalid',
            'sourcePaymentId' => 'b38e76c0-ac23-4c48-85fd-975f32c8809f',
            'paymentType' => 'form'
        ]));

        self::assertEquals(422, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'email' => 'This value is not a valid email address.'
            ],
        ], $data);
    }

    public function testInvalidSourcePaymentId(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/process-payment', [
            'email' => 'test@user.ru',
            'sourcePaymentId' => 'someInvalidSourcePaymentId',
            'paymentType' => 'form'
        ]));

        self::assertEquals(422, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'sourcePaymentId' => 'This is not a valid UUID.',
            ]
        ], $data);
    }


}
