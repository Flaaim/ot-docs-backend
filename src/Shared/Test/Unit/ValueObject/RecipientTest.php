<?php

namespace App\Shared\Test\Unit\ValueObject;

use App\Payment\Entity\Email;
use App\Shared\Domain\Service\Template\TemplatePath;
use App\Shared\Domain\ValueObject\File;
use App\Shared\Domain\ValueObject\Recipient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Recipient::class)]
class RecipientTest extends TestCase
{
    public function testSuccess(): void
    {
        $recipient = new Recipient(new Email('some@email.ru'), 'Оплата формы на сайте');
        $recipient->addAttachment(new File('file1.txt', new TemplatePath(sys_get_temp_dir())));
        $recipient->addAttachment(new File('file2.txt', new TemplatePath(sys_get_temp_dir())));

        self::assertEquals('some@email.ru', $recipient->getEmail()->getValue());
        self::assertEquals('Оплата формы на сайте', $recipient->getSubject());
        self::assertCount(2, $recipient->getAttachments());
    }
}