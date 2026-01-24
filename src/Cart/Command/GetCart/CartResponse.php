<?php

namespace App\Cart\Command\GetCart;

use App\Cart\Entity\Cart;
use App\Cart\Entity\CartItem;
use App\Cart\Entity\DTO\CartItemDTO;


class CartResponse
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

    public static function fromCart(Cart $cart): CartResponse
    {
        $items = [];

        foreach ($cart->getItems() as $item) {
            /** @var CartItem $item */
            $items[] = new CartItemDTO(
                $item->getProductId(),
                $item->getName(),
                $item->getPrice()->getValue(),
                $item->getSku(),
            );
        }

        return new self(
            $cart->getId()->getValue(),
            $cart->isPaid(),
            $items,
            $cart->getTotalPrice()->getValue(),
            $cart->getItems()->count(),
        );
    }
}