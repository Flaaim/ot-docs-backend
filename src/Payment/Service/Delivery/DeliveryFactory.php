<?php

namespace App\Payment\Service\Delivery;

use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;

class DeliveryFactory
{
    public function __construct(
        private readonly array $deliveries
    ){

    }

    public function createDelivery(PaymentWebhookDataInterface $paymentWebHookData): void{
        foreach ($this->deliveries as $delivery) {
            /** @var DeliveryInterface $delivery */
            if($delivery->supports($paymentWebHookData->getMetadata('type'))) {
                $delivery->deliver($paymentWebHookData);
                return;
            }
        }
        throw new \DomainException("Unsupported type in delivery");
    }

}