<?php

namespace App\Cart\Command\AddItem;

use App\Cart\Command\CartAwareHandler;
use App\Cart\Entity\Cart;
use App\Cart\Entity\CartItem;
use App\Cart\Entity\CartRepository;
use App\Flusher;
use App\Product\Entity\ProductRepository;
use App\Shared\Domain\ValueObject\Id;

class Handler extends CartAwareHandler
{
    public function __construct(
        private ProductRepository $products,
        private CartRepository $carts,
        private Flusher $flusher
    ) {
        parent::__construct($carts);
    }
    public function handle(Command $command): void
    {
        $product = $this->products->get(new Id($command->productId));

        $cart = $this->getOrCreateCart(new Id($command->cartId));

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
