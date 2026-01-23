<?php

namespace App\Cart\Entity;

use App\Product\Entity\Currency;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'carts')]
class Cart
{
    #[ORM\OneToMany(targetEntity: CartItem::class, mappedBy: 'cart', cascade: ['persist'], orphanRemoval: true)]
    private ArrayCollection $items;
    private function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'id', unique: true)]
        private Id $id,
        #[ORM\Column(type:'datetime_immutable')]
        private \DateTimeImmutable $createdAt,
        #[ORM\Column(type: 'boolean')]
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
    public static function createEmpty(): self
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
        $cartItem->appendCart($this);
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
        if($this->isEmpty()) {
            throw new \DomainException('Cart is empty.');
        }
        $this->items->clear();
    }
    public function markAsPaid(): void
    {
        $this->isPaid = true;
    }
    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }

    public function getTotalPrice(): Price
    {
        $sum = 0;
        if($this->isEmpty()) {
            return new Price(0.00, new Currency('RUB'));
        }
        foreach ($this->items as $item) {
            /** @var CartItem $item */
            $sum += $item->getPrice()->getValue();
        }
        return new Price($sum, new Currency('RUB'));
    }
}
