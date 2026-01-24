<?php

namespace App\Payment\Command\CreatePayment\Form;

class Command
{
    public function __construct(
        public string $email,
        public string $productId,
    ) {
    }
}
