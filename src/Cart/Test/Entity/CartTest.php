<?php

namespace App\Cart\Test\Entity;

use App\Cart\Entity\Cart;
use App\Cart\Test\Builder\CartItemBuilder;
use App\Product\Entity\Currency;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Cart::class)]
class CartTest extends TestCase
{
    public function testSuccess(): void
    {
        $cart = Cart::createEmpty();
        self::assertInstanceOf(Id::class, $cart->getId());
        self::assertInstanceOf(\DateTimeImmutable::class, $cart->getCreatedAt());
        self::assertEquals([], $cart->getItems()->toArray());
        self::assertFalse($cart->isPaid());
    }

    public function testAddItem(): void
    {
        $cart = Cart::createEmpty();
        $item = (new CartItemBuilder())->build();
        $item2 = (new CartItemBuilder())->withId(Id::generate())->build();

        $cart->addItem($item);
        $cart->addItem($item2);

        self::assertCount(2, $cart->getItems());
    }
    public function testExistingItem(): void
    {
        $cart = Cart::createEmpty();
        $item = (new CartItemBuilder())->build();
        $cart->addItem($item);

        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Product item already exists.');

        $cart->addItem($item);
    }
    public function testRemoveNotExistingItem(): void
    {
        $cart = Cart::createEmpty();
        $cartItem = (new CartItemBuilder())->build();

        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Product item does not exist in the cart.');

        $cart->removeItemByProductId($cartItem->getProductId());
    }
    public function testRemoveItem(): void
    {
        $cart = Cart::createEmpty();
        $item1 = (new CartItemBuilder())->build();

        $cart->addItem($item1);
        self::assertCount(1, $cart->getItems()->toArray());

        $cart->removeItemByProductId($item1->getProductId());
        self::assertCount(0, $cart->getItems()->toArray());
    }

    public function testClearCart(): void
    {
        $cart = Cart::createEmpty();

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
        $cart = Cart::createEmpty();

        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Cart is empty.');
        $cart->clear();
    }

    public function testPaid(): void
    {
        $cart = Cart::createEmpty();
        $cart->markAsPaid();
        self::assertTrue($cart->isPaid());
    }

    public function testGetTotalPrice(): void
    {
        $cart = Cart::createEmpty();

        $item1 = (new CartItemBuilder())
            ->withId(Id::generate())
            ->withPrice(new Price(200.00, new Currency('RUB')))->build();

        $item2 = (new CartItemBuilder())
            ->withId(Id::generate())
            ->withPrice(new Price(300.00, new Currency('RUB')))->build();

        $item3 = (new CartItemBuilder())
            ->withId(Id::generate())
            ->withPrice(new Price(400.00, new Currency('RUB')))->build();

        $cart->addItem($item1);
        $cart->addItem($item2);
        $cart->removeItemByProductId($item1->getProductId());
        $cart->addItem($item3);

        self::assertEquals(700.00, $cart->getTotalPrice()->getValue());

    }
}
