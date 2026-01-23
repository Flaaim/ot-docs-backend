<?php

namespace App\Cart\Command\GetCart;

use App\Cart\Command\CartAwareHandler;
use App\Cart\Entity\CartRepository;
use App\Shared\Domain\ValueObject\Id;

class Handler extends CartAwareHandler
{
    public function __construct(
        private CartRepository $carts,
    )
    {
        parent::__construct($carts);
    }

    public function handle(Command $command): Response
    {
        $cart = $this->getOrCreateCart(new Id($command->cartId));

        return Response::fromCart($cart);
    }

}