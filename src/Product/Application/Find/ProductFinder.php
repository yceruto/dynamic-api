<?php

namespace App\Product\Application\Find;

use App\Product\Domain\Error\ProductNotFoundError;
use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\ProductId;
use App\Product\Domain\Repository\ProductRepository;

readonly class ProductFinder
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function find(ProductId $id): Product
    {
        return $this->repository->ofId($id) ?? throw ProductNotFoundError::create($id);
    }

    /**
     * @return Product[]
     */
    public function search(): array
    {
        return $this->repository->all();
    }
}
