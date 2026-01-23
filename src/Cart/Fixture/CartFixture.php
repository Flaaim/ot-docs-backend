<?php

namespace App\Cart\Fixture;

use App\Cart\Entity\Cart;
use App\Cart\Test\Builder\CartItemBuilder;
use App\Product\Entity\Currency;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class CartFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {
        $cart = new Cart(
            new Id('ee971ef5-1683-4a69-955a-9e03f5880397'),
            new \DateTimeImmutable()
        );

        $item1 = (new CartItemBuilder())->withId(Id::generate())->withPrice(new Price(350.00, new Currency()))->build();
        $item2 = (new CartItemBuilder())->withId(Id::generate())->withPrice(new Price(350.00, new Currency()))->build();
        $item3 = (new CartItemBuilder())->withId(Id::generate())->withPrice(new Price(350.00, new Currency()))->build();
        $item4 = (new CartItemBuilder())->withId(Id::generate())->withPrice(new Price(350.00, new Currency()))->build();

        $cart->addItem($item1);
        $cart->addItem($item2);
        $cart->addItem($item3);
        $cart->addItem($item4);

        $manager->persist($cart);

        $manager->flush();
    }
}