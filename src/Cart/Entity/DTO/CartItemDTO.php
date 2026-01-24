<?php

namespace App\Cart\Entity\DTO;

class CartItemDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public float $price,
        public string $sku,
    ){}
}