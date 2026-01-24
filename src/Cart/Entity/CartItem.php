<?php

namespace App\Cart\Entity;

use App\Shared\Domain\ValueObject\File;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'cart_items')]
class CartItem
{
    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'items')]
    #[ORM\JoinColumn(name: 'cart_id', referencedColumnName: 'id', nullable: false)]
    private Cart $cart;
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'id', unique: true)]
        private Id $productId,
        #[ORM\Column(type: 'string', length: 255)]
        private string $name,
        #[ORM\Column(type: 'price')]
        private Price $price,
        #[ORM\Column(type: 'string', length: 25)]
        private string $sku,
        #[ORM\Column(type: 'file')]
        private File $file,
    ) {
    }
    public function getProductId(): Id
    {
        return $this->productId;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getPrice(): Price
    {
        return $this->price;
    }
    public function getSku(): string
    {
        return $this->sku;
    }
    public function getFile(): File
    {
        return $this->file;
    }
    public function getCart(): Cart
    {
        return $this->cart;
    }
    public function appendCart(Cart $cart): void
    {
        $this->setCart($cart);
    }
    private function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }
}
