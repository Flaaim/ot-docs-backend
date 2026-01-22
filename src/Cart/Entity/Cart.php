<?php

namespace App\Cart\Entity;

use App\Shared\Domain\ValueObject\Id;
use Doctrine\Common\Collections\ArrayCollection;

class Cart
{
    public function __construct(
        private Id $id,
        private \DateTimeImmutable $createdAt,
        private ArrayCollection $items,
        private bool $isPaid = false,
    ) {
        $this->items = new ArrayCollection();
    }

    public function getId(): Id
    {
        return $this->id;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getItems(): ArrayCollection
    {
        return $this->items;
    }
    public function isPaid(): bool
    {
        return $this->isPaid;
    }
}
