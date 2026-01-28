<?php

namespace App\Payment\Test\Service\Delivery\FormDelivery;

use App\Payment\Service\Delivery\FormDelivery\FormDelivery;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;
use App\Shared\Domain\Service\Template\TemplatePath;
use App\Shared\Domain\ValueObject\File;
use App\Shared\Domain\ValueObject\Id;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Test\Functional\Payment\ProductBuilder;

#[CoversClass(FormDelivery::class)]
class FormDeliveryTest extends TestCase
{
    private ProductRepository $productsMock;
    private TemplatePath $templatePath;
    private PaymentWebhookDataInterface $paymentWebhookMock;
    public function setUp(): void
    {
        $this->productsMock = $this->createMock(ProductRepository::class);
        $this->templatePath = new TemplatePath(sys_get_temp_dir());
        $this->paymentWebhookMock = $this->createMock(PaymentWebhookDataInterface::class);
    }
    public function testSuccess(): void
    {
        $productId = '1252f161-259d-4390-8c7c-d2c27eaaaa71';
        $email = 'some@email.ru';

        $formDelivery = new FormDelivery($this->productsMock, $this->templatePath);

        $this->paymentWebhookMock->expects($this->exactly(2))
            ->method('getMetadata')
            ->willReturnCallback(fn($key) => match ($key) {
               'sourcePaymentId' => $productId,
                'email' => $email,
                default => null,
            });

        $id = new Id($productId);
        $this->productsMock->expects($this->once())->method('get')->with(
            $this->equalTo($id)
        )->willReturn((new ProductBuilder())->withId($id)->build());

        $recipient = $formDelivery->deliver($this->paymentWebhookMock);

        self::assertEquals('some@email.ru', $recipient->getEmail()->getValue());
        self::assertEquals('Успешная оплата на сайте через форму', $recipient->getSubject());
        self::assertCount(1, $recipient->getAttachments());

        /** @var array<File> $files */
        $files = $recipient->getAttachments()->toArray();
        self::assertEquals('file.txt', $files[0]->getValue());
        self::assertEquals('/tmp/file.txt', $files[0]->getFullPath());
    }

    public function testEmptyMetadata(): void
    {
        $productId = null;
        $email = null;

        $formDelivery = new FormDelivery($this->productsMock, $this->templatePath);

        $this->paymentWebhookMock->expects($this->exactly(2))
            ->method('getMetadata')
            ->willReturnCallback(fn($key) => match ($key) {
                'sourcePaymentId' => $productId,
                'email' => $email,
                default => null,
            });

        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Missing required metadata in webhook.');
        $formDelivery->deliver($this->paymentWebhookMock);
    }

}
