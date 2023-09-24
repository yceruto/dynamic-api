<?php

namespace App\Product\Infrastructure\Persistence\InMemory;

use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\ProductId;
use App\Product\Domain\Model\ProductStatus;
use App\Product\Domain\Repository\ProductRepository;
use Money\Money;

class InMemoryProductRepository implements ProductRepository
{
    /**
     * @var array<string, Product>
     */
    private array $products;

    public function __construct()
    {
        $this->products = [
            '3fa85f64-5717-4562-b3fc-2c963f66afa6' => Product::create(
                ProductId::fromString('3fa85f64-5717-4562-b3fc-2c963f66afa6'),
                'iPhone X',
                Money::EUR(99900),
                ProductStatus::PUBLISHED,
            ),
        ];
    }

    public function add(Product $product): void
    {
        $this->products[$product->id()->value()] = $product;
    }

    public function remove(Product $product): void
    {
        unset($this->products[$product->id()->value()]);
    }

    public function ofId(ProductId $id): ?Product
    {
        return $this->products[$id->value()] ?? null;
    }

    public function all(): array
    {
        return array_values($this->products);
    }
}
