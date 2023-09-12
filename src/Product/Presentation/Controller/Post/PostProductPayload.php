<?php

namespace App\Product\Presentation\Controller\Post;

use App\Shared\Domain\View\MoneyAware;
use App\Shared\Presentation\Request\Model\MoneyPayload;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema]
class PostProductPayload implements MoneyAware
{
    #[OA\Property]
    #[Assert\Uuid]
    #[Groups('Default')]
    public ?string $id = null;

    #[OA\Property]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[Groups('Default')]
    public string $name;

    #[OA\Property]
    #[Assert\Valid(groups: ['Money'])]
    #[Groups('Money')]
    public MoneyPayload $price;
}
