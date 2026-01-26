<?php

namespace App\Shared\Test\Unit\ValueObject;

use App\Payment\Entity\Email;
use App\Shared\Domain\Service\Template\TemplateManager;
use App\Shared\Domain\Service\Template\TemplatePath;
use App\Shared\Domain\ValueObject\File;
use App\Shared\Domain\ValueObject\Recipient;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Test\Functional\Payment\ProductBuilder;

class RecipientTest extends TestCase
{
    public function testSuccess(): void
    {
        $product = (new ProductBuilder())->withFile(new File('/path-to-file'))->build();

        $recipient = new Recipient(new Email('some@email.ru'), new ArrayCollection([
            new TemplateManager(new TemplatePath(sys_get_temp_dir()), $product->getFile()),
        ]));

        self::assertEquals('some@email.ru', $recipient->getEmail()->getValue());
        self::assertCount(1, $recipient->getAttachments());
    }
}