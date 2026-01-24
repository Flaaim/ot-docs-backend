<?php

namespace Test\Functional\Cart\AddItem;

use Test\Functional\Json;
use Test\Functional\WebTestCase;

class RequestActionTest extends WebTestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([
            RequestFixture::class,
        ]);
    }
    public function testSuccess(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/add-item', [
            'productId' => '2c4ff038-7546-4619-8ed7-dac217afddcf',
            'cartId' => '6e648ddf-1fe2-4c63-9ebc-b5c3f02ce221',
        ]));

        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals('', (string)$response->getBody());
    }

    public function testInvalid(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/add-item', [
            'productId' => 'invalid-string',
            'cartId' => 'invalid-string',
        ]));

        self::assertEquals(422, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'productId' => 'This is not a valid UUID.',
                'cartId' => 'This is not a valid UUID.',
            ]
        ], $data);
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/add-item'));

        self::assertEquals(422, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'productId' => 'This value should not be blank.',
                'cartId' => 'This value should not be blank.',
            ]
        ], $data);
    }

    public function testExistingItem(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/add-item', [
            'productId' => '2c4ff038-7546-4619-8ed7-dac217afddcf',
            'cartId' => '6e648ddf-1fe2-4c63-9ebc-b5c3f02ce221',
        ]));

        self::assertEquals(201, $response->getStatusCode());

        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/add-item', [
            'productId' => '2c4ff038-7546-4619-8ed7-dac217afddcf',
            'cartId' => '6e648ddf-1fe2-4c63-9ebc-b5c3f02ce221',
        ]));

        self::assertEquals(400, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());
        $data = Json::decode($body);

        self::assertEquals([
            'message' => 'Product item already exists.'
        ], $data);

    }
}