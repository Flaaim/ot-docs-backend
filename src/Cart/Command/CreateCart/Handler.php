<?php

namespace App\Cart\Command\CreateCart;

use App\Cart\Entity\Cart;
use App\Cart\Entity\CartRepository;
use App\Shared\Domain\ValueObject\Id;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

class Handler
{
    public function __construct(private readonly CartRepository $carts)
    {
    }
    public function handle(Command $command): Response
    {
        $cart = $this->carts->find(new Id($command->id));
        if (null !== $cart) {
            return new Response($cart->getId()->getValue());
        } else {
            new Cart(
                $id = new Id(Uuid::uuid4()->toString()),
                new \DateTimeImmutable(),
                new ArrayCollection()
            );
            return new Response($id);
        }
    }
}
