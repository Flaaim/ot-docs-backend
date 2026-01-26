<?php

namespace App\Payment\Test\Command\CreatePayment\Cart;

use App\Cart\Entity\Cart;
use App\Cart\Entity\CartRepository;
use App\Payment\Command\CreatePayment\Cart\CreatePaymentCart;
use App\Payment\Command\CreatePayment\Request\Command;
use PHPUnit\Framework\TestCase;

class CreatePaymentCartTest extends TestCase
{
    public function testSuccess(): void
    {
        $payment = new CreatePaymentCart($carts = $this->createMock(CartRepository::class));

        $command = new Command(
            'email@test.ru',
            '36bf1194-be75-447a-91ee-ce67172b1c49',
            'cart'
        );

        $carts->expects(self::once())->method('find')->willReturn($this->createMock(Cart::class));

        $payment->preparePayment($command);
    }
    public function testSupport(): void
    {
        $payment = new CreatePaymentCart($carts = $this->createMock(CartRepository::class));
        self::assertTrue($payment->supports('cart'));
    }
    public function testUnsupported(): void
    {
        $payment = new CreatePaymentCart($carts = $this->createMock(CartRepository::class));
        self::assertFalse($payment->supports('invalid'));
    }
}