<?php

namespace App\Cart\Entity;

use App\Shared\Domain\ValueObject\Id;

interface CartRepository
{
    public function create(Cart $cart): void;
    public function find(Id $id): ?Cart;
    public function upsert(Cart $cart): void;
}
