<?php

namespace App\Cart\Command\AddItem;

use App\Cart\Entity\Cart;
use App\Cart\Entity\CartItem;
use App\Cart\Entity\CartRepository;
use App\Flusher;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\ValueObject\Id;

class Handler
{
    public function __construct(
        private ProductRepository $products,
        private CartRepository $carts,
        private Flusher $flusher
    ) {
    }
    public function handle(Command $command): void
    {
        $product = $this->products->get(new Id($command->productId));

        $cart = $this->carts->find(new Id($command->cartId));
        if (null === $cart) {
            $cart = Cart::create();

        }

        $cartItem = new CartItem(
            $product->getId(),
            $product->getName(),
            $product->getPrice(),
            $product->getCipher(),
            $product->getFile()
        );

        $cart->addItem($cartItem);

        $this->carts->upsert($cart);

        $this->flusher->flush();
    }
}
