<?php

namespace App\Product\Domain\Repository;

use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\ProductId;

interface ProductRepository
{
    public function add(Product $product): void;

    public function remove(Product $product): void;

    public function ofId(ProductId $id): ?Product;

    /**
     * @return Product[]
     */
    public function all(): array;
}
