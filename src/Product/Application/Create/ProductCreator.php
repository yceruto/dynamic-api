<?php

namespace App\Product\Application\Create;

use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\ProductId;
use App\Product\Domain\Model\ProductStatus;
use App\Product\Domain\Repository\ProductRepository;
use Money\Money;

readonly class ProductCreator
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function create(ProductId $id, string $name, Money $price, ProductStatus $status): Product
    {
        $product = Product::create($id, $name, $price, $status);
        $this->repository->add($product);

        return $product;
    }
}
