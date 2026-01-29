<?php

namespace Test\Functional\Payment\HookPayment\Cart;

use App\Cart\Test\Builder\CartBuilder;
use App\Cart\Test\Builder\CartItemBuilder;
use App\Shared\Domain\ValueObject\Id;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Test\Functional\Payment\FileBuilder;
use Test\Functional\Payment\PaymentBuilder;

class RequestFixture extends AbstractFixture
{

    public function load(ObjectManager $manager): void
    {

        $item1 = (new CartItemBuilder())
            ->withId(new Id('06857e68-160c-42f7-897c-e47671fd2629'))
            ->withFile((new FileBuilder())->withValue('file_item1.txt')->build())
            ->build();

        $item2 = (new CartItemBuilder())
            ->withId(new Id('5d30a1a1-8202-4635-b490-54d930d01854'))
            ->withFile((new FileBuilder())->withValue('file_item2.txt')->build())
            ->build();

        $cart = (new CartBuilder())
            ->withId(new Id('b38e76c0-ac23-4c48-85fd-975f32c8801f'))
            ->withItems(new ArrayCollection([$item1, $item2]))
            ->build();

        $manager->persist($cart);


        $payment = (new PaymentBuilder())
            ->withExternalId('hook_test_payment_id')
            ->build();

        $manager->persist($payment);

        $manager->flush();
    }


}