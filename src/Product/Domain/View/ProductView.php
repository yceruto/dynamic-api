<?php

namespace App\Product\Domain\View;

use App\Product\Domain\Model\Product;
use App\Shared\Domain\View\MoneyAware;
use App\Shared\Domain\View\MoneyView;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

#[OA\Schema]
readonly class ProductView implements MoneyAware
{
    #[OA\Property(format: 'uuid')]
    #[Groups('Default')]
    public string $id;

    #[OA\Property]
    #[Groups('Default')]
    public string $name;

    #[OA\Property]
    #[Groups('Money')]
    public MoneyView $price;

    /**
     * @param Product[] $products
     * @return self[]
     */
    public static function createMany(array $products): array
    {
        return array_map(
            static fn (Product $product) => self::create($product),
            $products,
        );
    }

    public static function create(Product $product): self
    {
        return new self($product);
    }

    private function __construct(Product $product)
    {
        $this->id = $product->id()->value();
        $this->name = $product->name();
        $this->price = MoneyView::create($product->price());
    }
}
