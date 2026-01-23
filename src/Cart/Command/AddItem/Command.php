<?php

namespace App\Cart\Command\AddItem;

use Webmozart\Assert\Assert;

class Command
{
    public function __construct(
        public string $productId,
        public string $cartId
    ) {
        Assert::uuid($productId);
        Assert::uuid($cartId);
    }
}
