<?php

namespace App\Shared\Presentation\Request\Model;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema]
class MoneyPayload
{
    #[OA\Property(example: 100)]
    #[Assert\PositiveOrZero]
    public int $amount;

    #[OA\Property(example: 'EUR')]
    #[Assert\Currency]
    public string $currency;
}
