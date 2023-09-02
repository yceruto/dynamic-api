<?php

namespace App\Product\Application\Find;

use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\ProductId;
use App\Product\Domain\Repository\ProductRepository;
use DomainException;

readonly class ProductFinder
{
    public function __construct(private ProductRepository $repository)
    {
    }

    public function find(ProductId $id): Product
    {
        return $this->repository->ofId($id) ?? throw new DomainException('Product not found');
    }

    /**
     * @return Product[]
     */
    public function search(): array
    {
        return $this->repository->all();
    }
}
