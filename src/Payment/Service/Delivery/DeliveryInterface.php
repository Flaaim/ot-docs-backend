<?php

namespace App\Payment\Service\Delivery;

use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;

interface DeliveryInterface
{
    public function deliver(PaymentWebhookDataInterface $paymentWebHookData): void;

    public function supports(string $type): bool;
}
