<?php

namespace App\Cart\Test\Entity;
use App\Cart\Entity\CartItem;
use App\Cart\Test\Builder\CartItemBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
#[CoversClass(CartItem::class)]
class CartItemTest extends TestCase
{
    public function testSuccess(): void
    {
        $cartItem = (new CartItemBuilder())->build();

        self::assertEquals('e63290b2-33e9-4c90-918f-4b28ceb42ca0', $cartItem->getProductId()->getValue());
        self::assertEquals(
            'Приказ о создании нештатного аварийно-спасательного формирования',
            $cartItem->getName()
        );
        self::assertEquals(350.00, $cartItem->getPrice()->getValue());
        self::assertEquals('ОТ-ПР', $cartItem->getSku());
        self::assertEquals(
            'safety/orders/prikaz-o-sozdaniy.docx',
            $cartItem->getFile()->getValue()
        );
    }
}