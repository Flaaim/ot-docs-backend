<?php

namespace App\Cart\Command\GetCart;

use App\Cart\Entity\Cart;


class Response
{
    public function __construct(
        public string $cartId,
        public bool $isPaid,
        public array $items,
        public float $totalPrice,
        public int $count,

    )
    {

    }

    public static function fromCart(Cart $cart): Response
    {
        return new self(
            $cart->getId()->getValue(),
            $cart->isPaid(),
            $cart->getItems()->toArray(),
            $cart->getTotalPrice()->getValue(),
            $cart->getItems()->count(),
        );
    }
}