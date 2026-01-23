<?php

namespace App\Cart\Command;

use App\Cart\Entity\Cart;
use App\Cart\Entity\CartRepository;
use App\Shared\Domain\ValueObject\Id;

class CartAwareHandler
{
    public function __construct(
        private CartRepository $carts,
    ) {
    }
    protected function getOrCreateCart(Id $cartId): Cart
    {
        $cart = $this->carts->find($cartId);

        if (null === $cart) {
            $cart = Cart::create();
            $this->carts->upsert($cart);
        }

        return $cart;
    }
}