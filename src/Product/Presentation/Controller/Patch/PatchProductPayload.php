<?php

namespace App\Product\Presentation\Controller\Patch;

use App\Shared\Presentation\Request\Model\MoneyPayload;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema]
class PatchProductPayload
{
    #[OA\Property]
    #[Assert\Length(min: 3)]
    public ?string $name = null;

    #[OA\Property]
    #[Assert\Valid]
    public ?MoneyPayload $price = null;
}
