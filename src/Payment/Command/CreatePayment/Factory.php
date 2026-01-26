<?php

namespace App\Payment\Command\CreatePayment;

use App\Payment\Command\CreatePayment\Request\Command;
use App\Payment\Command\CreatePayment\Request\Handler;

class Factory
{
    public function __construct(
        private readonly Handler $handler,
        private readonly array   $creators
    )
    {
       foreach ($this->creators as $creator) {
           if(!$creator instanceof CreatePaymentInterface) {
               throw new \DomainException("Handlers must be instance of CreatePaymentInterface.");
           }
       }
    }

    public function createPayment(Command $command): Response
    {
        foreach ($this->creators as $creator) {
            /** @var CreatePaymentInterface $creator */
            if ($creator->supports($command->paymentType)) {
                $payment = $creator->preparePayment($command);
                return $this->handler->handle($payment);
            }
        }
        throw new \DomainException('Unsupported payment type');
    }
}