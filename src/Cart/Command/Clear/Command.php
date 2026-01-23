<?php

namespace App\Cart\Command\Clear;



use Webmozart\Assert\Assert;

class Command
{
    public function __construct(public string $cartId)
    {
       Assert::uuid($this->cartId);
    }
}