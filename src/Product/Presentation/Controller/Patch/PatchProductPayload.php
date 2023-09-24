<?php

namespace App\Product\Presentation\Controller\Patch;

use App\Product\Domain\Model\ProductStatus;
use App\Shared\Domain\Publisher\MoneyFeaturePublisher;
use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\OpenApi\Attributes\Schema;
use App\Shared\Presentation\Request\Model\MoneyPayload;
use Symfony\Component\Validator\Constraints as Assert;

#[Schema]
class PatchProductPayload
{
    #[Property(minLength: 3, groups: ['Default'])]
    public ?string $name = null;

    #[Property(groups: ['Money'], publisher: MoneyFeaturePublisher::class)]
    public ?MoneyPayload $price = null;

    #[Property(enum: ProductStatus::class, groups: ['Default'])]
    public ?string $status = null;
}
