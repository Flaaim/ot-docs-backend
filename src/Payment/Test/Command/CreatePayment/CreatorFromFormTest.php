<?php

namespace App\Payment\Test\Command\CreatePayment;

use App\Payment\Command\CreatePayment\CreatePaymentCommand;
use App\Payment\Command\CreatePayment\CreatePaymentResponse;
use App\Payment\Command\CreatePayment\CreatorFromForm;
use App\Payment\Command\CreatePayment\Form\Command;
use App\Payment\Command\CreatePayment\Form\Handler;
use PHPUnit\Framework\TestCase;

class CreatorFromFormTest extends TestCase
{
    public function testSuccess(): void
    {
        $creator = new CreatorFromForm($handler = $this->createMock(Handler::class));

        $command = new CreatePaymentCommand(
            'email@test.ru',
            '36bf1194-be75-447a-91ee-ce67172b1c49',
            'form'
        );

        $handler->expects($this->once())->method('handle')
            ->with($this->equalTo(
                new Command($command->email, $command->sourcePaymentId)
            ))->willReturn($this->createMock(CreatePaymentResponse::class));

        $creator->createPayment($command);
    }
    public function testSupport(): void
    {
        $creator = new CreatorFromForm($this->createMock(Handler::class));
        self::assertTrue($creator->supports('form'));
    }
    public function testUnsupported(): void
    {
        $creator = new CreatorFromForm($this->createMock(Handler::class));
        self::assertFalse($creator->supports('invalid'));
    }
}