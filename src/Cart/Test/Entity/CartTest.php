<?php

namespace App\Cart\Test\Entity;

use App\Cart\Entity\Cart;
use App\Shared\Domain\ValueObject\Id;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

#[CoversClass(Cart::class)]
class CartTest extends TestCase
{
    public function testSuccess(): void
    {
        $cart = new Cart(
            $id = new Id(Uuid::uuid4()->toString()),
            $date = new \DateTimeImmutable(),
            $items = new ArrayCollection([])
        );

        self::assertEquals($id, $cart->getId());
        self::assertEquals($date, $cart->getCreatedAt());
        self::assertEquals($items, $cart->getItems());
        self::assertFalse($cart->isPaid());
    }
}
