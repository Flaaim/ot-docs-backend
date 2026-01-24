<?php

namespace App\Cart\Test\Command\GetCart;

use App\Cart\Command\GetCart\Command;
use App\Cart\Command\GetCart\Handler;
use App\Cart\Entity\Cart;
use App\Cart\Entity\CartRepository;
use App\Cart\Test\Builder\CartBuilder;
use App\Cart\Test\Builder\CartItemBuilder;
use App\Product\Entity\Currency;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class GetCartTest extends TestCase
{

    public function testSuccess(): void
    {
        $command = new Command(Id::generate());
        $handler = new Handler($carts = $this->createMock(CartRepository::class));

        $carts->expects($this->once())
            ->method('find')
            ->willReturn(
                $cart = (new CartBuilder())->withItems(
                    new ArrayCollection([
                        (new CartItemBuilder())->withId(Id::generate())->withPrice(new Price(350.00, new Currency()))->build(),
                        (new CartItemBuilder())->withId(Id::generate())->withPrice(new Price(350.00, new Currency()))->build(),
                        (new CartItemBuilder())->withId(Id::generate())->withPrice(new Price(350.00, new Currency()))->build(),
                        (new CartItemBuilder())->withId(Id::generate())->withPrice(new Price(350.00, new Currency()))->build(),
                    ])
                )->build()
            );

        $response = $handler->handle($command);

        self::assertEquals(1400.00, $response->totalPrice);
        self::assertEquals($cart->getId()->getValue(), $response->cartId);

    }
    public function testEmptyCart(): void
    {
        $command = new Command($cartId = Id::generate());
        $handler = new Handler($carts = $this->createMock(CartRepository::class));

        $carts->expects($this->once())->method('find')->willReturn($cart = Cart::createEmpty());

        $response = $handler->handle($command);

        self::assertCount(0, $response->items);
        self::assertFalse($response->isPaid);
        self::assertEquals(0, $response->totalPrice);
    }
}