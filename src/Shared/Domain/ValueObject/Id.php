<?php

namespace App\Shared\Domain\ValueObject;

use App\Cart\Entity\CartItem;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Id
{
    private string $value;
    public function __construct(string $value)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
    }
    public function getValue(): string
    {
        return $this->value;
    }
    public static function generate(): self
    {
        return new self(Uuid::uuid4()->toString());
    }
    public function equals(Id $id): bool
    {
        return $this->value === $id->getValue();
    }
    public function __toString(): string
    {
        return $this->value;
    }
}
