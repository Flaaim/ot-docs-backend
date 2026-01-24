<?php

namespace App\Cart\Command\RemoveItem;

use App\Cart\Command\CartAwareHandler;
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
        $cart = $this->getOrCreateCart(new Id($command->cartId));

        $product = $this->products->get(new Id($command->productId));

        $cart->removeItemByProductId($product->getId());

        $this->carts->update($cart);

        $this->flusher->flush();
    }
}