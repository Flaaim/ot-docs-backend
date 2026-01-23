<?php

namespace App\Cart\Test\Entity;

use App\Cart\Entity\CartItem;
use App\Cart\Test\Builder\CartItemBuilder;
use App\Shared\Domain\ValueObject\Id;
use PHPUnit\Framework\TestCase;

class CartItemTest extends TestCase
{
    public function testSuccess(): void
    {
        $cartItem = (new CartItemBuilder())->build();

        self::assertEquals('e63290b2-33e9-4c90-918f-4b28ceb42ca0', $cartItem->getId()->getValue());
        self::assertEquals(
            'Приказ о создании нештатного аварийно-спасательного формирования',
            $cartItem->getName()
        );
        self::assertEquals(350.00, $cartItem->getPrice()->getValue());
        self::assertEquals('ОТ-ПР', $cartItem->getCipher());
        self::assertEquals(
            'safety/orders/prikaz-o-sozdaniy.docx',
            $cartItem->getFile()->getPathToFile()
        );
    }

    public function testEquals(): void
    {
        $item = (new CartItemBuilder())->withId(new Id('79af9c9b-dc67-48dc-88b3-5f58cf06f393'))->build();
        $item2 = (new CartItemBuilder())->withId(new Id('d2ca41e2-4e90-4b76-ac62-2d1b759a77ca'))->build();

        self::assertTrue($item->equals($item));
        self::assertFalse($item->equals($item2));
    }
}