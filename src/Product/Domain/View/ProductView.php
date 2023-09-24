<?php

namespace App\Product\Domain\View;

use App\Product\Domain\Model\Product;
use App\Product\Domain\Model\ProductStatus;
use App\Product\Domain\Provider\ProductGroupsProvider;
use App\Shared\Domain\Publisher\MoneyFeaturePublisher;
use App\Shared\Domain\View\MoneyView;
use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\OpenApi\Attributes\Schema;
use Symfony\Component\Serializer\Annotation\Groups;

#[Schema(groupsProvider: ProductGroupsProvider::class)]
readonly class ProductView
{
    #[Property(format: 'uuid', groups: ['Default'])]
    #[Groups('Default')]
    public string $id;

    #[Property(groups: ['Default'])]
    #[Groups('Default')]
    public string $name;

    #[Property(groups: ['Default'], publisher: MoneyFeaturePublisher::class)]
    #[Groups('Money')]
    public MoneyView $price;

    #[Property(groups: ['Default'])]
    #[Groups('Default')]
    public ProductStatus $status;

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
        $this->status = $product->status();
    }
}
