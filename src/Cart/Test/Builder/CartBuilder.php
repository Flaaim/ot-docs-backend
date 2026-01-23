<?php

namespace App\Cart\Test\Builder;

use App\Cart\Entity\Cart;
use App\Shared\Domain\ValueObject\Id;
use Doctrine\Common\Collections\ArrayCollection;

class CartBuilder
{
    private Id $id;
    private \DateTimeImmutable $createdAt;
    private ArrayCollection $items;
    private bool $isPaid;

    public function __construct()
    {
        $this->id = new Id('1f136920-e4c3-4490-9dfa-e4a95a75837b');
        $this->createdAt = new \DateTimeImmutable('now');
        $this->items = new ArrayCollection();
        $this->isPaid = false;
    }
    public function withCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    public function withItems(ArrayCollection $items): self
    {
        $this->items = $items;
        return $this;
    }
    public function withPaid(): self
    {
        $this->isPaid = true;
        return $this;
    }

    public function build(): Cart
    {
        $cart = Cart::createEmpty();

        foreach ($this->items as $item) {
            $cart->addItem($item);
        }
        if($this->isPaid) {
            $cart->markAsPaid();
        }
        return $cart;
    }
}