<?php

namespace App\Payment\Test\Command\CreatePayment\Form;

use App\Payment\Command\CreatePayment\Form\CreatePaymentForm;
use App\Payment\Command\CreatePayment\Request\Command;
use App\Product\Entity\Product;
use App\Product\Entity\ProductRepository;
use PHPUnit\Framework\TestCase;

class CreatePaymentFormTest extends TestCase
{
    public function testSuccess(): void
    {
        $payment = new CreatePaymentForm($products = $this->createMock(ProductRepository::class));

        $command = new Command(
            'email@test.ru',
            '36bf1194-be75-447a-91ee-ce67172b1c49',
            'form'
        );

        $products->expects(self::once())->method('get')->willReturn($this->createMock(Product::class));

        $payment->preparePayment($command);
    }
    public function testSupport(): void
    {
        $creator = new CreatePaymentForm($this->createMock(ProductRepository::class));
        self::assertTrue($creator->supports('form'));
    }
    public function testUnsupported(): void
    {
        $creator = new CreatePaymentForm($this->createMock(ProductRepository::class));
        self::assertFalse($creator->supports('invalid'));
    }
}