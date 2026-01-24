<?php

namespace Test\Functional\Cart\Get;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{
    use ArraySubsetAsserts;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([
            RequestFixture::class,
        ]);
    }
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('GET', '/payment-service/carts/get?id=6e648ddf-1fe2-4c63-9ebc-b5c3f02ce221'));

        self::assertEquals(200, $response->getStatusCode());

        self::assertJson($body = (string) $response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'cartId' => '6e648ddf-1fe2-4c63-9ebc-b5c3f02ce221',
            'isPaid' => false,
            'items' => [
                [
                    'id' => '94ef4960-b770-408e-8181-9c16fa5d6852',
                    'name' => 'Приказ о создании нештатного аварийно-спасательного формирования',
                    'price' => 350,
                    'sku' => 'ОТ-ПР'
                ]
            ],
            'totalPrice' => 350,
            'count' => 1,
        ], $data);
    }

    public function testNotFound(): void
    {
        $response = $this->app()->handle(self::json('GET', '/payment-service/carts/get?id=94ef4960-b770-408e-8181-9c16fa5d6852'));

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($body = (string) $response->getBody());

        $data = Json::decode($body);

        self::assertArraySubset([
            'isPaid' => false,
            'items' => [],
            'totalPrice' => 0,
            'count' => 0,
        ], $data);
    }

    public function testInvalid(): void
    {
        $response = $this->app()->handle(self::json('GET', '/payment-service/carts/get?id=invalid'));

        self::assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string) $response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'cartId' => 'This is not a valid UUID.',
            ]
        ], $data);
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('GET', '/payment-service/carts/get?id='));

        self::assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string) $response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'cartId' => 'This value should not be blank.',
            ]
        ], $data);
    }
}