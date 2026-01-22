<?php

namespace App\Cart\Command\CreateCart;

use Webmozart\Assert\Assert;

class Command
{
    public function __construct(
        public readonly string $id
    )
    {
        Assert::uuid($id);
    }
}