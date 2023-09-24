<?php

namespace App\Product\Presentation\Controller\Put;

use App\Product\Domain\Model\ProductStatus;
use App\Shared\Domain\Publisher\MoneyFeaturePublisher;
use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\OpenApi\Attributes\Schema;
use App\Shared\Presentation\Request\Model\MoneyPayload;

#[Schema]
class PutProductPayload
{
    #[Property(minLength: 3, groups: ['Default'])]
    public string $name;

    #[Property(groups: ['Money'], publisher: MoneyFeaturePublisher::class)]
    public MoneyPayload $price;

    #[Property(enum: ProductStatus::class, groups: ['Default'])]
    public string $status;
}
