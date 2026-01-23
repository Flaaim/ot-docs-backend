<?php

namespace App\Cart\Command\GetCart;

use Webmozart\Assert\Assert;

class Command
{
    public function __construct(
        public string $cartId
    )
    {
        Assert::uuid($this->cartId);
    }
}