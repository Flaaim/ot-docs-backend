<?php

namespace App\Payment\Service\Delivery;

use App\Payment\Entity\Email;
use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;

class DeliveryFactory
{
    public function __construct(
        private readonly array $deliveries,
        private readonly SenderInterface $sender,
    ){

    }

    public function createDelivery(PaymentWebhookDataInterface $paymentWebHookData): void {
        foreach ($this->deliveries as $delivery) {
            /** @var DeliveryInterface $delivery */
            if($delivery->supports($paymentWebHookData->getMetadata('type'))) {
                $recipient = $delivery->deliver($paymentWebHookData);
                $this->sender->send($recipient);
                return;
            }
        }
        throw new \DomainException("Unsupported type in delivery");
    }

}