<?php

namespace Test\Functional\Cart\Clear;

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
        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/clear', [
            'cartId' => '6e648ddf-1fe2-4c63-9ebc-b5c3f02ce221'
        ]));

        self::assertEquals(200, $response->getStatusCode());

        self::assertEquals('', (string)$response->getBody());
    }

    public function testClearAlready(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/clear', [
            'cartId' => '6e648ddf-1fe2-4c63-9ebc-b5c3f02ce221'
        ]));

        self::assertEquals(200, $response->getStatusCode());

        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/clear', [
            'cartId' => '6e648ddf-1fe2-4c63-9ebc-b5c3f02ce221'
        ]));

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'message' => 'Cart is empty.',
        ], $data);
    }

    public function testEmpty(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/clear'));

        self::assertEquals(422, $response->getStatusCode());

        $body = (string)$response->getBody();
        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'cartId' => 'This value should not be blank.'
            ]
        ], $data);
    }

    public function testInvalid(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/clear', [
            'cartId' => 'invalid'
        ]));

        self::assertEquals(422, $response->getStatusCode());

        $body = (string)$response->getBody();
        $data = Json::decode($body);

        self::assertEquals([
            'errors' => [
                'cartId' => 'This is not a valid UUID.'
            ]
        ], $data);
    }

    public function testClearPaid(): void
    {
        $response = $this->app()->handle(self::json('POST', '/payment-service/carts/clear', [
            'cartId' => '94ef4960-b770-408e-8181-9c16fa5d6852'
        ]));

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($body = (string)$response->getBody());

        $data = Json::decode($body);

        self::assertEquals([
            'message' => 'Can not clear cart with paid items.',
        ], $data);
    }
}