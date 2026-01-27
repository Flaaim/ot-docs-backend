<?php

namespace App\Payment\Service\Delivery;

use App\Shared\Domain\Service\Payment\PaymentWebhookDataInterface;
use App\Shared\Domain\ValueObject\Recipient;

interface DeliveryInterface
{
    public function deliver(PaymentWebhookDataInterface $paymentWebHookData): Recipient;

    public function supports(string $type): bool;
}
