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
        $item2 = (new CartItemBuilder())->withId(Id::generate())->build();

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
    public function testRemoveNotExistingItem(): void
    {
        $cart = Cart::create();
        $cartItem = (new CartItemBuilder())->build();

        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Product item does not exist in the cart.');

        $cart->removeItemByProductId($cartItem->getProductId());
    }
    public function testRemoveItem(): void
    {
        $cart = Cart::create();
        $item1 = (new CartItemBuilder())->build();

        $cart->addItem($item1);
        self::assertCount(1, $cart->getItems()->toArray());

        $cart->removeItemByProductId($item1->getProductId());
        self::assertCount(0, $cart->getItems()->toArray());
    }

    public function testClearCart(): void
    {
        $cart = Cart::create();

        $cart->addItem((new CartItemBuilder())->withId(Id::generate())->build());
        $cart->addItem((new CartItemBuilder())->withId(Id::generate())->build());
        $cart->addItem((new CartItemBuilder())->withId(Id::generate())->build());
        $cart->addItem((new CartItemBuilder())->withId(Id::generate())->build());

        self::assertCount(4, $cart->getItems()->toArray());
        $cart->clear();
        self::assertCount(0, $cart->getItems()->toArray());
    }

    public function testClearEmptyCart(): void
    {
        $cart = Cart::create();

        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Cart is empty.');
        $cart->clear();
    }
}
