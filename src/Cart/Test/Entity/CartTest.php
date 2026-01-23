<?php

namespace App\Cart\Test\Entity;

use App\Cart\Entity\Cart;
use App\Cart\Test\Builder\CartItemBuilder;
use App\Shared\Domain\ValueObject\Id;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

#[CoversClass(Cart::class)]
class CartTest extends TestCase
{
    public function testSuccess(): void
    {
        $cart = Cart::create();
        self::assertInstanceOf(Id::class, $cart->getId());
        self::assertInstanceOf(\DateTimeImmutable::class, $cart->getCreatedAt());
        self::assertEquals([], $cart->getItems()->toArray());
        self::assertFalse($cart->isPaid());
    }

    public function testAddItem(): void
    {
        $cart = Cart::create();
        $item = (new CartItemBuilder())->build();
        $item2 = (new CartItemBuilder())->withId(new Id(Uuid::uuid4()->toString()))->build();
        $cart->addItem($item);
        $cart->addItem($item2);
        self::assertCount(2, $cart->getItems());
    }
    public function testExistingItem(): void
    {
        $cart = Cart::create();
        $item = (new CartItemBuilder())->build();
        $cart->addItem($item);
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Product item already exists.');
        $cart->addItem($item);

    }
}
