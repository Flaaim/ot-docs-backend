<?php

namespace App\Cart\Command\RemoveItem;

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
        $cart = $this->carts->find(new Id($command->cartId));

        $product = $this->products->get(new Id($command->productId));

        $cart->removeItemByProductId($product->getId());

        $this->carts->upsert($cart);

        $this->flusher->flush();
    }
}