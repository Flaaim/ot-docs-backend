<?php

namespace App\Payment\Test\Service\Delivery\CartDelivery;

use App\Cart\Entity\Cart;
use App\Cart\Entity\CartRepository;
use App\Cart\Test\Builder\CartBuilder;
use App\Cart\Test\Builder\CartItemBuilder;
use App\Payment\Service\Delivery\CartDelivery\CartDelivery;
use App\Payment\Service\Delivery\FormDelivery\FormDelivery;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;
use App\Shared\Domain\Service\Template\TemplatePath;
use App\Shared\Domain\ValueObject\File;
use App\Shared\Domain\ValueObject\Id;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Test\Functional\Payment\ProductBuilder;


class CartDeliveryTest extends TestCase
{
    private CartRepository $cartsMock;
    private TemplatePath $templatePath;
    private PaymentWebhookDataInterface $paymentWebhookMock;
    public function setUp(): void
    {
        $this->cartsMock = $this->createMock(CartRepository::class);
        $this->templatePath = new TemplatePath(sys_get_temp_dir());
        $this->paymentWebhookMock = $this->createMock(PaymentWebhookDataInterface::class);
    }
    public function testSuccess(): void
    {
        $cartId = '1252f161-259d-4390-8c7c-d2c27eaaaa71';
        $email = 'some@email.ru';

        $formDelivery = new CartDelivery($this->cartsMock, $this->templatePath);

        $this->paymentWebhookMock->expects($this->exactly(2))
            ->method('getMetadata')->willReturnCallback(fn($key) => match($key) {
                'sourcePaymentId' => $cartId,
                'email' => $email,
                default => null,
            });

        $id = new Id($cartId);
        $this->cartsMock->expects($this->once())->method('find')->with(
            $this->equalTo($id)
        )->willReturn($this->getCart($id));

        $recipient = $formDelivery->deliver($this->paymentWebhookMock);
        self::assertEquals($email, $recipient->getEmail()->getValue());
        self::assertEquals('Оплата корзины на сайте', $recipient->getSubject());
        self::assertCount(4, $recipient->getAttachments());

        /** @var array<File> $files */
        $files = $recipient->getAttachments()->toArray();
        self::assertEquals('file1', $files[0]->getValue());
        self::assertEquals('file2', $files[1]->getValue());
        self::assertEquals('file3', $files[2]->getValue());
        self::assertEquals('file4', $files[3]->getValue());
    }

    public function testEmptyMetadata(): void
    {
        $cartId = null;
        $email = null;

        $formDelivery = new CartDelivery($this->cartsMock, $this->templatePath);

        $this->paymentWebhookMock->expects($this->exactly(2))
            ->method('getMetadata')->willReturnCallback(fn($key) => match($key) {
                'sourcePaymentId' => $cartId,
                'email' => $email,
                default => null,
            });

        self::expectExceptionMessage(\DomainException::class);
        self::expectExceptionMessage('Missing required metadata in webhook.');

        $formDelivery->deliver($this->paymentWebhookMock);
    }

    private function getCart(Id $id): Cart
    {
        return (new CartBuilder())->withId($id)
            ->withItems(new ArrayCollection([
                (new CartItemBuilder())
                    ->withFile(new File('file1'))
                    ->withId(new Id('7fe8af5a-3a22-48eb-8013-8b272ba4ef12'))
                    ->build(),
                (new CartItemBuilder())
                    ->withFile(new File('file2'))
                    ->withId(new Id('3575966a-e2e2-4cdc-93c4-95f3372e04ad'))
                    ->build(),
                (new CartItemBuilder())
                    ->withFile(new File('file3'))
                    ->withId(new Id('dfaa15e4-29fe-452a-bb34-8a4aeca46811'))
                    ->build(),
                (new CartItemBuilder())
                    ->withFile(new File('file4'))
                    ->withId(new Id('06857e68-160c-42f7-897c-e47671fd2629'))
                    ->build(),
            ]))

            ->build();
    }

}