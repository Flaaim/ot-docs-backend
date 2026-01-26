<?php

namespace App\Cart\Command;

use App\Cart\Entity\Cart;
use App\Cart\Entity\CartRepository;
use App\Shared\Domain\ValueObject\Id;

abstract class CartAwareHandler
{
    public function __construct(
        private CartRepository $carts,
    ) {
    }
    protected function getOrCreateCart(Id $cartId): Cart
    {
        $cart = $this->carts->find($cartId);

        if (null === $cart) {
            $cart = Cart::createEmpty();
            $this->carts->create($cart);
        }

        return $cart;
    }
}