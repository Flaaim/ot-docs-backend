<?php

namespace App\Payment\Service\Delivery\FormDelivery;

use App\Payment\Entity\Email;
use App\Payment\Entity\PaymentType;
use App\Payment\Service\Delivery\DeliveryInterface;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;
use App\Shared\Domain\Service\Template\TemplatePath;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Recipient;

class FormDelivery implements DeliveryInterface
{
    public function __construct(
        private readonly ProductRepository $products,
        private readonly TemplatePath $templatePath,
    ) {
    }
    public function deliver(PaymentWebhookDataInterface $paymentWebHookData): Recipient
    {
        $productId = $paymentWebHookData->getMetadata('productId');
        $email = $paymentWebHookData->getMetadata('email');

        if (!$email || !$productId) {
            throw new \DomainException('Missing required metadata in webhook');
        }
        $product = $this->products->get(new Id($productId));
        $email = new Email($paymentWebHookData->getMetadata('email'));

        $recipient = new Recipient($email, 'Успешная оплата на сайте через форму');
        $file = $product->getFile();

        $file->mergePaths($this->templatePath);
        $recipient->addAttachment($file);
        return $recipient;
    }

    public function supports(string $type): bool
    {
        return PaymentType::FORM->value === $type;
    }
}