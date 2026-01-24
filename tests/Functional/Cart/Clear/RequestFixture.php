<?php

namespace Test\Functional\Cart\Clear;

use App\Cart\Entity\Cart;
use App\Cart\Test\Builder\CartBuilder;
use App\Cart\Test\Builder\CartItemBuilder;
use App\Shared\Domain\ValueObject\Id;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Test\Functional\Payment\ProductBuilder;

class RequestFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {

        $product = (new ProductBuilder())->withId(new Id('2c4ff038-7546-4619-8ed7-dac217afddcf'))->build();

        $manager->persist($product);

        $cart = new Cart(
            new Id('6e648ddf-1fe2-4c63-9ebc-b5c3f02ce221'),
            new \DateTimeImmutable()
        );
        $item = (new CartItemBuilder())->withId(Id::generate())->build();

        $cart->addItem($item);

        $manager->persist($cart);

        $paidCart = (new CartBuilder())->withId(new Id('94ef4960-b770-408e-8181-9c16fa5d6852'))->withPaid()->build();

        $item = (new CartItemBuilder())->withId(Id::generate())->build();

        $cart->addItem($item);

        $manager->persist($paidCart);

        $manager->flush();
    }
}