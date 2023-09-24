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
    #[Property]
    #[Assert\Length(min: 3)]
    public ?string $name = null;

    #[Property(publisher: MoneyFeaturePublisher::class)]
    #[Assert\Valid]
    public ?MoneyPayload $price = null;

    #[Property(enum: [ProductStatus::DRAFT->value, ProductStatus::PUBLISHED->value, ProductStatus::ARCHIVED->value], groups: ['Default'])]
    public ?string $status = null;
}
