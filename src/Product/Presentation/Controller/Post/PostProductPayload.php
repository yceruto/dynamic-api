<?php

namespace App\Product\Presentation\Controller\Post;

use App\Product\Domain\Provider\ProductGroupsProvider;
use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\OpenApi\Attributes\Schema;
use App\Shared\Presentation\Request\Model\MoneyPayload;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[Schema(groupsProvider: ProductGroupsProvider::class)]
class PostProductPayload
{
    #[Property(format: 'uuid', groups: ['Default'])]
    #[Assert\Uuid]
    #[Groups('Default')]
    public ?string $id = null;

    #[Property(maxLength: 3, groups: ['Default'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[Groups('Default')]
    public string $name;

    #[Property(groups: ['Money'])]
    #[Assert\Valid(groups: ['Money'])]
    #[Groups('Money')]
    public MoneyPayload $price;
}
