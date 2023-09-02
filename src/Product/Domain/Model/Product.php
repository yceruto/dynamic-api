<?php

namespace App\Product\Domain\Model;

use Money\Money;

class Product
{
    public static function create(ProductId $id, string $name, Money $price): self
    {
        return new self($id, $name, $price);
    }

    public function update(string $name, Money $price): void
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function id(): ProductId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): Money
    {
        return $this->price;
    }

    private function __construct(
        private readonly ProductId $id,
        private string $name,
        private Money $price,
    ) {
    }
}
