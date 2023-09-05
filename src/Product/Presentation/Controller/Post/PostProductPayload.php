<?php

namespace App\Product\Presentation\Controller\Post;

use App\Shared\Presentation\Request\Model\MoneyPayload;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema]
class PostProductPayload
{
    #[OA\Property]
    #[Assert\Uuid]
    public ?string $id = null;

    #[OA\Property]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    public string $name;

    #[OA\Property]
    #[Assert\Valid]
    public MoneyPayload $price;
}
