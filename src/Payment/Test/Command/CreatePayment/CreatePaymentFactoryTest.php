<?php

namespace App\Payment\Test\Command\CreatePayment;

use App\Cart\Entity\Cart;
use App\Cart\Entity\CartRepository;
use App\Payment\Command\CreatePayment\Cart\CreatePaymentCart;
use App\Payment\Command\CreatePayment\Factory;
use App\Payment\Command\CreatePayment\Form\CreatePaymentForm;
use App\Payment\Command\CreatePayment\Request\Command;
use App\Payment\Command\CreatePayment\Request\Handler;
use App\Payment\Command\CreatePayment\Response;
use App\Payment\Entity\Payment;
use App\Product\Entity\Product;
use App\Product\Entity\ProductRepository;
use PHPUnit\Framework\TestCase;
use stdClass;


class CreatePaymentFactoryTest extends TestCase
{
    public function testFormSuccess(): void
    {
        $handler = $this->createMock(Handler::class);
        $paymentFromForm = new CreatePaymentForm($products = $this->createMock(ProductRepository::class));

        $factory = new Factory($handler, [$paymentFromForm]);

        $createPaymentCommand = new Command(
            'email@test.ru',
            '84fdd534-7d27-471d-aff1-98fd6ec528b5',
            'form'
        );

        $products->expects($this->once())
            ->method('get')
            ->willReturn($this->createMock(Product::class));

        $handler->expects($this->once())->method('handle')->with(
            $this->isInstanceOf(Payment::class)
        )->willReturn($this->createMock(Response::class));

        $factory->createPayment($createPaymentCommand);

    }

    public function testCartSuccess(): void
    {
        $createPaymentHandler = $this->createMock(Handler::class);
        $creatorFromCart = new CreatePaymentCart($carts = $this->createMock(CartRepository::class));

        $factory = new Factory($createPaymentHandler, [$creatorFromCart]);

        $createPaymentCommand = new Command(
            'email@test.ru',
            '84fdd534-7d27-471d-aff1-98fd6ec528b5',
            'cart'
        );

        $carts->expects($this->once())->method('find')->willReturn($this->createMock(Cart::class));
        $createPaymentHandler->expects($this->once())->method('handle')
            ->with($this->isInstanceOf(Payment::class))
            ->willReturn($this->createMock(Response::class));

        $factory->createPayment($createPaymentCommand);
    }

    public function testCartFailed(): void
    {
        $createPaymentHandler = $this->createMock(Handler::class);
        $creatorFromCart = new CreatePaymentCart($carts = $this->createMock(CartRepository::class));

        $factory = new Factory($createPaymentHandler, [$creatorFromCart]);

        $createPaymentCommand = new Command(
            'email@test.ru',
            '84fdd534-7d27-471d-aff1-98fd6ec528b5',
            'cart'
        );
        $carts->expects($this->once())->method('find')
            ->willThrowException(new \DomainException('Cart to create payment not found.'));


        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Cart to create payment not found.');
        $factory->createPayment($createPaymentCommand);
    }

    public function testInvalidCreator(): void
    {
        $createPaymentHandler = $this->createMock(Handler::class);
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Handlers must be instance of CreatePaymentInterface.');
        new Factory($createPaymentHandler, [new StdClass(), new StdClass()]);
    }
}