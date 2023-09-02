<?php

namespace App\Product\Application\Update;

use App\Product\Application\Find\ProductFinder;
use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\ProductId;
use Money\Money;

readonly class ProductUpdater
{
    public function __construct(private ProductFinder $finder)
    {
    }

    public function update(ProductId $id, string $name, Money $price): Product
    {
        $product = $this->finder->find($id);
        $product->update($name, $price);

        return $product;
    }
}
