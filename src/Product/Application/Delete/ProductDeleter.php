<?php

namespace App\Product\Application\Delete;

use App\Product\Application\Find\ProductFinder;
use App\Product\Domain\Model\ProductId;
use App\Product\Domain\Repository\ProductRepository;

readonly class ProductDeleter
{
    public function __construct(
        private ProductFinder $finder,
        private ProductRepository $repository
    ) {
    }

    public function delete(ProductId $id): void
    {
        $product = $this->finder->find($id);
        $this->repository->remove($product);
    }
}
