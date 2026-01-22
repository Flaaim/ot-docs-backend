<?php

namespace App\Cart\Test\Builder;

use App\Cart\Entity\CartItem;
use App\Shared\Domain\ValueObject\Id;

class CartItemBuilder
{
    private Id $id;
    private string $name;
    private float $price;
    private string $cipher;
    public function __construct()
    {
        $this->id = new Id('e63290b2-33e9-4c90-918f-4b28ceb42ca0');
        $this->name = 'Приказ о создании нештатного аварийно-спасательного формирования';
        $this->price = 350.00;
        $this->cipher = 'ОТ-ПР';
    }

    public function withId(Id $id): self
    {
        $this->id = $id;
        return $this;
    }
    public function withName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    public function withPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }
    public function withCipher(string $cipher): self
    {
        $this->cipher = $cipher;
        return $this;
    }
    public function build(): CartItem
    {
        return new CartItem(
            $this->id,
            $this->name,
            $this->price,
            $this->cipher
        );
    }
}
