<?php

namespace App\Payment\Service\Delivery;

use App\Payment\Entity\Email;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;
use App\Shared\Domain\ValueObject\Id;

class DeliveryService implements DeliveryInterface
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly SenderInterface $sender
    ) {
    }
    public function deliver(PaymentWebhookDataInterface $paymentWebHookData): void
    {
        $productId = $paymentWebHookData->getMetadata('productId');
        $cartId = $paymentWebHookData->getMetadata('cartId');
        $email = $paymentWebHookData->getMetadata('email');

        if ((!$email && !$productId) || (!$email && !$cartId)) {
            throw new \DomainException('Missing required metadata in webhook');
        }
        /** @var SenderInterface $sender */
        $this->sender->send(
            new Email($email),
            $this->productRepository->get(new Id($productId))
        );
    }
}
