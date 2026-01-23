<?php

namespace App\Cart\Entity;

use App\Shared\Domain\ValueObject\File;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;

class CartItem
{
    public function __construct(
        private Id $productId,
        private string $name,
        private Price $price,
        private string $cipher,
        private File $file,
    ) {
    }


    public function getId(): Id
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
    public function getCipher(): string
    {
        return $this->cipher;
    }
    public function getFile(): File
    {
        return $this->file;
    }
    public function equals(CartItem $cartItem): bool
    {
        return $this->productId->getValue() === $cartItem->getId()->getValue();
    }
}
