<?php

namespace App\Payment\Test\Command\CreatePayment;

use App\Cart\Entity\Cart;
use App\Payment\Command\CreatePayment\CreatePaymentCommand;
use App\Payment\Command\CreatePayment\CreatePaymentFactory;
use App\Payment\Command\CreatePayment\CreatePaymentResponse;
use App\Payment\Command\CreatePayment\CreatorFromCart;
use App\Payment\Command\CreatePayment\CreatorFromForm;
use App\Payment\Command\CreatePayment\Form\Command;
use App\Payment\Command\CreatePayment\Form\Handler as FormHandler;
use App\Payment\Command\CreatePayment\Cart\Handler as CartHandler;
use App\Payment\Command\CreatePayment\Form\Command as FormCommand;
use App\Payment\Command\CreatePayment\Cart\Command as CartCommand;
use PHPUnit\Framework\TestCase;
use stdClass;


class CreatePaymentFactoryTest extends TestCase
{
    public function testFormSuccess(): void
    {
        $creatorFromForm = new CreatorFromForm($formHandler = $this->createMock(FormHandler::class));
        $creatorFromCart = new CreatorFromCart($cartHandler = $this->createMock(CartHandler::class));
        $factory = new CreatePaymentFactory([$creatorFromForm, $creatorFromCart]);

        $createPaymentCommand = new CreatePaymentCommand(
            'email@test.ru',
            '84fdd534-7d27-471d-aff1-98fd6ec528b5',
            'form'
        );

        $formHandler->expects($this->once())->method('handle')->with(
            $this->equalTo(new FormCommand('email@test.ru', '84fdd534-7d27-471d-aff1-98fd6ec528b5')),
        )->willReturn($this->createMock(CreatePaymentResponse::class));

        $factory->createPayment($createPaymentCommand);

    }

    public function testCartSuccess(): void
    {
        $creatorFromForm = new CreatorFromForm($formHandler = $this->createMock(FormHandler::class));
        $creatorFromCart = new CreatorFromCart($cartHandler = $this->createMock(CartHandler::class));
        $factory = new CreatePaymentFactory([$creatorFromForm, $creatorFromCart]);

        $createPaymentCommand = new CreatePaymentCommand(
            'email@test.ru',
            '84fdd534-7d27-471d-aff1-98fd6ec528b5',
            'cart'
        );

        $cartHandler->expects($this->once())->method('handle')->with(
            $this->equalTo(new CartCommand('email@test.ru', '84fdd534-7d27-471d-aff1-98fd6ec528b5')),
        )->willReturn($this->createMock(CreatePaymentResponse::class));

        $factory->createPayment($createPaymentCommand);
    }

    public function testInvalidCreator(): void
    {
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('Handlers must be instance of CreatePaymentInterface.');
        new CreatePaymentFactory([new StdClass(), new StdClass()]);
    }
}