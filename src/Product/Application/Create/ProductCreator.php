<?php

namespace App\Product\Application\Create;

use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\ProductId;
use App\Product\Domain\Repository\ProductRepository;
use Money\Money;

readonly class ProductCreator
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function create(ProductId $id, string $name, Money $price): Product
    {
        $product = Product::create($id, $name, $price);
        $this->repository->add($product);

        return $product;
    }
}
