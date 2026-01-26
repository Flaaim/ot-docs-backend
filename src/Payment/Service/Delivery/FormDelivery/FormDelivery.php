<?php

namespace App\Payment\Service\Delivery\FormDelivery;

use App\Payment\Entity\Email;
use App\Payment\Entity\PaymentType;
use App\Payment\Service\Delivery\DeliveryInterface;
use App\Payment\Service\Delivery\SenderInterface;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;
use App\Shared\Domain\ValueObject\Id;

class FormDelivery implements DeliveryInterface
{
    public function __construct(
        private readonly SenderInterface $sender,
        private readonly ProductRepository $products,
    ) {
    }
    public function deliver(PaymentWebhookDataInterface $paymentWebHookData): void
    {
        $productId = $paymentWebHookData->getMetadata('productId');
        $email = $paymentWebHookData->getMetadata('email');

        if (!$email || !$productId) {
            throw new \DomainException('Missing required metadata in webhook');
        }

        $product = $this->products->get(new Id($productId));

        $this->sender->send(new Email($email), $product);
    }

    public function supports(string $type): bool
    {
        return PaymentType::FORM->value === $type;
    }
}