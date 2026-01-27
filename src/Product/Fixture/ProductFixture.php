<?php

namespace App\Product\Fixture;

use App\Product\Entity\Currency;
use App\Product\Entity\Product;
use App\Shared\Domain\Service\Template\TemplatePath;
use App\Shared\Domain\ValueObject\File;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Test\Functional\Payment\FileBuilder;

class ProductFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $file = (new FileBuilder())->withValue('ppe/templates.txt')
            ->build();


        $product = new Product(
            new Id('b38e76c0-ac23-4c48-85fd-975f32c8801f'),
            'СИЗ образцы документов',
            new Price(450.00, new Currency()),
            $file,
            'ot161.4',
        );

        $manager->persist($product);

        $manager->flush();
    }
}
