<?php

namespace App\Payment\Command\CreatePayment;

use App\Payment\Command\CreatePayment\Request\Command;
use App\Payment\Entity\Payment;

interface CreatePaymentInterface {
    public function preparePayment(Command $command): Payment;
    public function supports(string $paymentType): bool;
}