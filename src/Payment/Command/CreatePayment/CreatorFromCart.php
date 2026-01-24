<?php

namespace App\Payment\Command\CreatePayment;

use App\Payment\Command\CreatePayment\Cart\Command;
use App\Payment\Command\CreatePayment\Cart\Handler;
use App\Payment\Entity\PaymentType;

class CreatorFromCart implements CreatePaymentInterface
{
    public function __construct(private Handler $handler){

    }
    public function createPayment(CreatePaymentCommand $command): CreatePaymentResponse
    {
       $command = new Command($command->email, $command->sourcePaymentId);
       return $this->handler->handle($command);
    }

    public function supports(string $paymentType): bool
    {
        return PaymentType::CART->value === $paymentType;
    }
}