<?php

namespace App\Product\Presentation\Controller\Post;

use App\Product\Domain\Model\ProductStatus;
use App\Product\Domain\Provider\ProductGroupsProvider;
use App\Shared\Domain\Money\Decider\MoneyFeatureDecider;
use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\OpenApi\Attributes\Schema;
use App\Shared\Presentation\Request\Money\MoneyPayload;

#[Schema(groupsProvider: ProductGroupsProvider::class)]
class PostProductPayload
{
    #[Property(format: 'uuid', groups: ['Default'])]
    public ?string $id = null;

    #[Property(minLength: 3, groups: ['Default'])]
    public string $name;

    #[Property(groups: ['Money'], decider: MoneyFeatureDecider::class)]
    public MoneyPayload $price;

    #[Property(enum: [ProductStatus::DRAFT, ProductStatus::PUBLISHED], groups: ['Default'])]
    public string $status;
}
