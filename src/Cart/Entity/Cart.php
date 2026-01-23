<?php

namespace App\Cart\Entity;

use App\Shared\Domain\ValueObject\Id;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

class Cart
{
    private ArrayCollection $items;
    private function __construct(
        private Id $id,
        private \DateTimeImmutable $createdAt,
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
    public static function create(): self
    {
        return new self(
            new Id(Uuid::uuid4()->toString()),
            new \DateTimeImmutable(),
        );
    }
    public function addItem(CartItem $cartItem): void
    {
        foreach ($this->items as $item) {
            /** @var CartItem $item */
            if($item->getProductId()->equals($cartItem->getProductId())) {
                throw new \DomainException('Product item already exists.');
            }
        }
        $this->items->add($cartItem);
    }
    public function removeItemByProductId(Id $productId): void
    {
        foreach ($this->items as $item) {
            /** @var CartItem $item */
            if($item->getProductId()->equals($productId)) {
                $this->items->removeElement($item);
                return;
            }
        }
        throw new \DomainException('Product item does not exist in the cart.');
    }

    public function clear(): void
    {
        if($this->items->isEmpty()) {
            throw new \DomainException('Cart is empty.');
        }
        $this->items->clear();
    }
    public function markAsPaid(): void
    {
        $this->isPaid = true;
    }
}
