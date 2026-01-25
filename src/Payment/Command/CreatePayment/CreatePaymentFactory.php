<?php

namespace App\Payment\Command\CreatePayment;

class CreatePaymentFactory
{
    public function __construct(
        private array $creators
    )
    {
       foreach ($this->creators as $creator) {
           if(!$creator instanceof CreatePaymentInterface) {
               throw new \DomainException("Handlers must be instance of CreatePaymentInterface.");
           }
       }
    }

    public function createPayment(CreatePaymentCommand $command): CreatePaymentResponse
    {
        foreach ($this->creators as $creator) {
            /** @var CreatePaymentInterface $creator */
            if ($creator->supports($command->paymentType)) {
                return $creator->createPayment($command);
            }
        }
        throw new \DomainException('Unsupported payment type');
    }
}