<?php

namespace App\Shared\Presentation\Request\Model;

use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema]
class MoneyPayload
{
    #[OA\Property(example: 100)]
    #[Assert\NotBlank(groups: ['Money'])]
    #[Assert\Positive(groups: ['Money'])]
    #[Groups('Money')]
    public int $amount;

    #[OA\Property(example: 'EUR')]
    #[Assert\NotBlank(groups: ['Money'])]
    #[Assert\Currency(groups: ['Money'])]
    #[Groups('Money')]
    public string $currency;
}
