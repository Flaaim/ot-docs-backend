<?php

namespace App\Product\Entity;

use App\Shared\Domain\ValueObject\File;
use App\Shared\Domain\ValueObject\Id;
use App\Shared\Domain\ValueObject\Price;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: 'id', unique: true)]
    private Id $id;
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;
    #[ORM\Column(type: 'string', length: 25, unique: true)]
    private string $sku;
    #[ORM\Column(type: 'price')]
    private Price $price;
    #[ORM\Column(type: 'file')]
    private File $file;
    public function __construct(Id $id, string $name, Price $price, File $file, string $sku)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->file = $file;
        $this->sku = $sku;
    }
    public function getId(): Id
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getPrice(): Price
    {
        return $this->price;
    }
    public function getFile(): File
    {
        return $this->file;
    }
    public function getSku(): string
    {
        return $this->sku;
    }
    public function update(string $name, Price $price, File $file): void
    {
        $this->name = $name;
        $this->price = $price;
        $this->file = $file;
    }
}
