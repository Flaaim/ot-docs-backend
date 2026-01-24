<?php

namespace App\Payment\Command\CreatePayment\Cart;

class Command
{
    public function __construct(
        public string $email,
        public string $cartId
    ){

    }
}