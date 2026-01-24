<?php

namespace App\Payment\Command\CreatePayment;

class CreatePaymentFactory
{
    public function __construct(
        private array $handlers
    )
    {
       foreach ($this->handlers as $handler) {
           if(!$handler instanceof CreatePaymentInterface) {
               throw new \DomainException("Handlers must be instance of CreatePaymentInterface");
           }
       }
    }


    public function createPayment(CreatePaymentCommand $command): CreatePaymentResponse
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($command->paymentType)) {
                return $handler->createPayment($command);
            }
        }
        throw new \DomainException('Unsupported payment type');
    }
}