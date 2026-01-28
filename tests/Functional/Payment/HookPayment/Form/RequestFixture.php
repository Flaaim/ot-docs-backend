<?php

namespace Test\Functional\Payment\HookPayment\Form;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Test\Functional\Payment\FileBuilder;
use Test\Functional\Payment\PaymentBuilder;
use Test\Functional\Payment\ProductBuilder;

class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $product = (new ProductBuilder())
            ->withFile((new FileBuilder())->build())
            ->build();

        $manager->persist($product);

        $payment = (new PaymentBuilder())
            ->withExternalId('hook_test_payment_id')
            ->build();

        $manager->persist($payment);

        $manager->flush();
    }
}
