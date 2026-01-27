<?php

namespace App\Payment\Test\Service;

use App\Payment\Entity\Email as UserEmail;
use App\Payment\Service\Delivery\Sender;
use App\Shared\Domain\Service\Template\TemplatePath;
use App\Shared\Domain\ValueObject\File as EntityFile;
use App\Shared\Domain\ValueObject\Recipient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Twig\Environment;

#[CoversClass(Sender::class)]
class SenderTest extends TestCase
{
    public function testSuccess()
    {
        $recipient = new Recipient(new UserEmail('test@app.ru'), 'Оплата формы на сайте');

        $twig = $this->createMock(Environment::class);
        $logger = $this->createMock(LoggerInterface::class);

        $message = (new Email())
            ->subject($recipient->getSubject())
            ->to($recipient->getEmail()->getValue())
            ->html($twig->render('mail/template.html.twig'));

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects($this->once())->method('send')->with(
            $this->equalTo($message),
        )->willReturnCallback(static function ($message) use ($twig, $recipient) {
            /** @var Email $message */
            self::assertEquals([new Address($recipient->getEmail()->getValue())], $message->getTo());
            self::assertEquals($recipient->getSubject(), $message->getSubject());
            self::assertEquals($twig->render('mail/template.html.twig'), $message->getHtmlBody());
        });

        $sender = new Sender($mailer, $twig, $logger);
        $sender->send($recipient);
    }
    public function testAttachment(): void
    {
        $file = $this->getFile('file.txt');
        $recipient = new Recipient(new UserEmail('test@app.ru'), 'Оплата формы на сайте');
        $recipient->addAttachment($file);

        $twig = $this->createMock(Environment::class);
        $logger = $this->createMock(LoggerInterface::class);
        $mailer = $this->createMock(MailerInterface::class);

        $message = (new Email())
            ->subject($recipient->getSubject())
            ->to($recipient->getEmail()->getValue())
            ->addPart(new DataPart(new File($file->getFile())))
            ->html($twig->render('mail/template.html.twig'));

        $mailer->expects($this->once())->method('send')->with(
            $this->equalTo($message),
        )->willReturnCallback(static function ($message) use ($twig, $recipient) {
            /** @var Email $message */
            self::assertEquals([new Address($recipient->getEmail()->getValue())], $message->getTo());
            self::assertEquals($recipient->getSubject(), $message->getSubject());
            self::assertEquals($twig->render('mail/template.html.twig'), $message->getHtmlBody());
            self::assertEquals([
                new DataPart(new File($recipient->getAttachments()[0]->getFile()))
            ],
                $message->getAttachments());
        });

        $sender = new Sender($mailer, $twig, $logger);
        $sender->send($recipient);
    }

    public function testMultipleAttachments(): void
    {
        $file1 = $this->getFile('file1.txt');
        $file2 = $this->getFile('file2.txt');

        $recipient = new Recipient(new UserEmail('test@app.ru'), 'Оплата формы на сайте');
        $recipient->addAttachment($file1);
        $recipient->addAttachment($file2);

        $twig = $this->createMock(Environment::class);
        $logger = $this->createMock(LoggerInterface::class);
        $mailer = $this->createMock(MailerInterface::class);

        $message = (new Email())
            ->subject($recipient->getSubject())
            ->to($recipient->getEmail()->getValue())
            ->addPart(new DataPart(new File($file1->getFile())))
            ->addPart(new DataPart(new File($file2->getFile())))
            ->html($twig->render('mail/template.html.twig'));

        $mailer->expects($this->once())->method('send')->with(
            $this->equalTo($message),
        )->willReturnCallback(static function ($message) use ($twig, $recipient) {
            /** @var Email $message */
            self::assertEquals([new Address($recipient->getEmail()->getValue())], $message->getTo());
            self::assertEquals($recipient->getSubject(), $message->getSubject());
            self::assertEquals($twig->render('mail/template.html.twig'), $message->getHtmlBody());
            self::assertEquals([
                new DataPart(new File($recipient->getAttachments()[0]->getFile())),
                new DataPart(new File($recipient->getAttachments()[1]->getFile())),
            ],
                $message->getAttachments());
        });

        $sender = new Sender($mailer, $twig, $logger);
        $sender->send($recipient);
    }

    public function testFailed(): void
    {
        $recipient = new Recipient(new UserEmail('test@app.ru'), 'Оплата на сайте');
        $mailer = $this->createMock(MailerInterface::class);
        $twig = $this->createMock(Environment::class);
        $logger = $this->createMock(LoggerInterface::class);

        $mailer->expects($this->once())->method('send')->willThrowException(new TransportException());

        $productSender = new Sender($mailer, $twig, $logger);

        $this->expectException(TransportException::class);
        $productSender->send($recipient);
    }

    private function getFile(string $name): EntityFile
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $name;
        file_put_contents($path, 'some content');

        $file =  new EntityFile($name);
        $file->mergePaths(new TemplatePath(sys_get_temp_dir()));
        return $file;

    }
}
