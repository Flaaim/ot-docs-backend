<?php

namespace App\Cart\Command\RemoveItem;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $productId,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $cartId
    )
    {
    }
}