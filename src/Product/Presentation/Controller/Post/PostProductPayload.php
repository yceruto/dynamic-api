<?php

namespace App\Product\Presentation\Controller\Post;

use App\Product\Domain\Provider\ProductGroupsProvider;
use App\Shared\Domain\Publisher\MoneyFeaturePublisher;
use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\OpenApi\Attributes\Schema;
use App\Shared\Presentation\Request\Model\MoneyPayload;
use Symfony\Component\Validator\Constraints as Assert;

#[Schema(groupsProvider: ProductGroupsProvider::class)]
class PostProductPayload
{
    #[Property(format: 'uuid')]
    #[Assert\Uuid(groups: ['Default'])]
    public ?string $id = null;

    #[Property(maxLength: 3)]
    #[Assert\NotBlank(groups: ['Default'])]
    #[Assert\Length(min: 3, groups: ['Default'])]
    public string $name;

    #[Property(publisher: MoneyFeaturePublisher::class)]
    #[Assert\NotBlank(groups: ['Money'])]
    #[Assert\Valid(groups: ['Money'])]
    public MoneyPayload $price;
}
