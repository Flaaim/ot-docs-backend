<?php

namespace Test\Functional\Payment\CreatePayment\Cart;



use App\Cart\Test\Builder\CartBuilder;
use App\Cart\Test\Builder\CartItemBuilder;
use App\Shared\Domain\ValueObject\Id;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class RequestFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {
       $cart = (new CartBuilder())->withId(new Id('9a786be6-4363-43ab-ba86-2ee791258fdc'))
           ->withItems(new ArrayCollection([
                   (new CartItemBuilder())->withId(new Id('9a97c21b-3f96-433b-9d51-4870c6bc9cce'))->build(),
                   (new CartItemBuilder())->withId(new Id('3c906ee3-ac29-4d21-ad39-1369983b2a7a'))->build(),
                   (new CartItemBuilder())->withId(new Id('13af3573-ccea-4979-bd02-bd01a079410b'))->build(),
                   (new CartItemBuilder())->withId(new Id('b11411c8-7b28-46e4-a1f7-1a3079039f37'))->build(),
               ])
           )
           ->build();

       $manager->persist($cart);

       $manager->flush();
    }
}