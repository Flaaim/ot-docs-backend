<?php

namespace Test\Functional\Cart\RemoveItem;


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
        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/remove-item', [
            'cartId' => '6e648ddf-1fe2-4c63-9ebc-b5c3f02ce221',
            'productId' => '94ef4960-b770-408e-8181-9c16fa5d6852',
        ]));

        self::assertEquals(200, $response->getStatusCode());

        self::assertEquals('', (string)$response->getBody());
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/remove-item'));

        self::assertEquals(422, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'cartId' => 'This value should not be blank.',
                'productId' => 'This value should not be blank.'
            ]
        ], $data);

    }

    public function testInvalid(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/remove-item', [
            'cartId' => 'invalid-string',
            'productId' => 'invalid-string',
        ]));

        self::assertEquals(422, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'cartId' => 'This is not a valid UUID.',
                'productId' => 'This is not a valid UUID.'
            ]
        ], $data);
    }

    public function testRemoveFromEmptyCart(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/remove-item', [
            'cartId' => '4f82710b-82df-4f65-8d82-f41074c7bbc3',
            'productId' => '94ef4960-b770-408e-8181-9c16fa5d6852',
        ]));

        self::assertEquals(400, $response->getStatusCode());

        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'message' => 'Product item does not exist in the cart.',
        ], $data);
    }
}