<?php

namespace App\Payment\Service\Delivery\CartDelivery;

use App\Cart\Entity\CartItem;
use App\Cart\Entity\CartRepository;
use App\Payment\Entity\Email;
use App\Payment\Entity\PaymentType;
use App\Payment\Service\Delivery\DeliveryInterface;
use App\Product\Entity\Product;
use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;
use App\Shared\Domain\Service\Template\TemplatePath;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Recipient;

class CartDelivery implements DeliveryInterface
{
    public function __construct(
        private readonly CartRepository $carts,
        private readonly TemplatePath $templatePath,
    ){
    }
    public function deliver(PaymentWebhookDataInterface $paymentWebHookData): Recipient
    {
        $cartId = $paymentWebHookData->getMetadata('sourcePaymentId');
        $email = $paymentWebHookData->getMetadata('email');

        if(!$cartId && !$email ) {
            throw new \DomainException('Missing required metadata in webhook.');
        }
        $cart = $this->carts->find(new Id($cartId));
        if(null === $cart) {
            throw new \DomainException('Cart not found.');
        }
        $recipient = new Recipient(new Email($email), 'Оплата корзины на сайте');

        foreach($cart->getItems() as $product) {
            /** @var Product $product */
            $file = $product->getFile();
            $file->mergePaths($this->templatePath);
            $recipient->addAttachment($file);
        }

        return $recipient;
    }

    public function supports(string $type): bool
    {
        return PaymentType::CART->value === $type;
    }
}