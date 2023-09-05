<?php

namespace App\Product\Presentation\Controller\Put;

use App\Shared\Presentation\Request\Model\MoneyPayload;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema]
class PutProductPayload
{
    #[OA\Property]
    #[Assert\Length(min: 3)]
    public string $name;

    #[OA\Property]
    #[Assert\Valid]
    public MoneyPayload $price;
}
