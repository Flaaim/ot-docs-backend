<?php

namespace App\Cart\Command\RemoveItem;

use Webmozart\Assert\Assert;

class Command
{
    public function __construct(
        public string $productId,
        public string $cartId
    )
    {
        Assert::uuid($this->productId);
    }
}