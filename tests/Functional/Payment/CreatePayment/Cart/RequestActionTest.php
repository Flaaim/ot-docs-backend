<?php

namespace Test\Functional\Payment\CreatePayment\Cart;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{
    use ArraySubsetAsserts;
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([RequestFixture::class]);
    }
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/process-payment', [
            'email' => 'test@app.ru',
            'sourcePaymentId' => '9a786be6-4363-43ab-ba86-2ee791258fdc',
            'paymentType' => 'cart'
        ]));

        $this->assertEquals(201, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset([
            'amount' => 1400,
            'currency' => 'RUB',
        ], $data);

    }

}