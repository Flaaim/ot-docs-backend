<?php

namespace App\Payment\Command\CreatePayment;

use App\Payment\Command\CreatePayment\Form\Command;
use App\Payment\Command\CreatePayment\Form\Handler;


class CreatorFromForm implements CreatePaymentInterface
{
    public function __construct(
        private Handler $handler
    )
    {
    }
    public const TYPE = 'form';
    public function createPayment(CreatePaymentCommand $command): CreatePaymentResponse
    {
        $command = new Command($command->email, $command->sourcePaymentId);
        return $this->handler->handle($command);
    }

    public function supports(string $paymentType): bool
    {
        return self::TYPE === $paymentType;
    }
}