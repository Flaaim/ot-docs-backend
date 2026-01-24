<?php

namespace App\Payment\Command\CreatePayment;



interface CreatePaymentInterface
{
    public function createPayment(CreatePaymentCommand $command): CreatePaymentResponse;

    public function supports(string $paymentType): bool;
}