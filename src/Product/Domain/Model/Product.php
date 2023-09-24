<?php

namespace App\Product\Domain\Model;

use Money\Money;

class Product
{
    public static function create(ProductId $id, string $name, Money $price, ProductStatus $status): self
    {
        return new self($id, $name, $price, $status);
    }

    public function update(string $name, Money $price, ProductStatus $status): void
    {
        $this->name = $name;
        $this->price = $price;
        $this->status = $status;
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

    public function status(): ProductStatus
    {
        return $this->status;
    }

    private function __construct(
        private readonly ProductId $id,
        private string $name,
        private Money $price,
        private ProductStatus $status,
    ) {
    }
}
