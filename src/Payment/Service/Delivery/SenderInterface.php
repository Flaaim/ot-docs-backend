<?php

namespace App\Payment\Service\Delivery;

use App\Shared\Domain\ValueObject\Recipient;

interface SenderInterface
{
    public function send(Recipient $recipient): void;
}