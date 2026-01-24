<?php

namespace Test\Functional\Cart\AddItem;

use App\Cart\Entity\Cart;
use App\Cart\Test\Builder\CartBuilder;
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
            new \DateTimeImmutable('now'),
        );

        $manager->persist($cart);

        $manager->flush();
    }
}