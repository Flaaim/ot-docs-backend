<?php

namespace Test\Functional\Product\Upsert;

use App\Product\Entity\Currency;
use App\Shared\Domain\Service\Template\TemplatePath;
use App\Shared\Domain\ValueObject\File;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Test\Functional\Payment\ProductBuilder;

class RequestFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $product = (new ProductBuilder())
            ->withId(new Id('b38e76c0-ac23-4c48-85fd-975f32c8801f'))
            ->withName('ПИ 1791.10 Итоговое тестирование по Программе IП')
            ->withPrice(new Price(550.00, new Currency('RUB')))
            ->withFile(new File('fire/1791/pi1791.10.docx', new TemplatePath(sys_get_temp_dir())))
            ->withSku('1791')
            ->build();

        $manager->persist($product);

        $manager->flush();
    }
}
