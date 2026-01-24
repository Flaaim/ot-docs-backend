<?php

namespace App\Cart\Test\Builder;

use App\Cart\Entity\CartItem;
use App\Product\Entity\Currency;
use App\Shared\Domain\ValueObject\File;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;

class CartItemBuilder
{
    private Id $id;
    private string $name;
    private Price $price;
    private string $course;
    private File $file;
    public function __construct()
    {
        $this->id = new Id('e63290b2-33e9-4c90-918f-4b28ceb42ca0');
        $this->name = 'Приказ о создании нештатного аварийно-спасательного формирования';
        $this->price = new Price(350.00, new Currency('RUB'));
        $this->course = 'ОТ-ПР';
        $this->file = new File('safety/orders/prikaz-o-sozdaniy.docx');
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
    public function withPrice(Price $price): self
    {
        $this->price = $price;
        return $this;
    }
    public function withCourse(string $course): self
    {
        $this->course = $course;
        return $this;
    }
    public function withFile(File $file): self
    {
        $this->file = $file;
        return $this;
    }
    public function build(): CartItem
    {
        return new CartItem(
            $this->id,
            $this->name,
            $this->price,
            $this->course,
            $this->file
        );
    }
}
