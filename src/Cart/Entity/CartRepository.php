<?php

namespace App\Cart\Entity;

use App\Shared\Domain\ValueObject\Id;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CartRepository
{
    private EntityRepository $repo;
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $repo = $em->getRepository(Cart::class);
        $this->repo = $repo;
        $this->em = $em;
    }
    public function find(Id $id): ?Cart
    {
        $cart = $this->repo->find($id);
        if (null === $cart) {
            return null;
        }
        return $cart;
    }
    public function update(Cart $cart): void
    {
        $this->em->persist($cart);
    }
    public function create(Cart $cart): void
    {
        $this->em->persist($cart);
    }
}
