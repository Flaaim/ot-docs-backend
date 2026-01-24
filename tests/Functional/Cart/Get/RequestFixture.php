<?php

namespace Test\Functional\Cart\Get;

use App\Cart\Entity\Cart;
use App\Cart\Test\Builder\CartItemBuilder;
use App\Shared\Domain\ValueObject\Id;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class RequestFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {
        $cart = new Cart(
            new Id('6e648ddf-1fe2-4c63-9ebc-b5c3f02ce221'),
            new \DateTimeImmutable()
        );
        $item = (new CartItemBuilder())->withId(new Id('94ef4960-b770-408e-8181-9c16fa5d6852'))->build();

        $cart->addItem($item);

        $manager->persist($cart);

        $manager->flush();
    }
}