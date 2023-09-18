<?php

namespace App\Shared\Presentation\Request\Model;

use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\OpenApi\Attributes\Schema;
use Symfony\Component\Validator\Constraints as Assert;

#[Schema]
class MoneyPayload
{
    #[Property(example: 100)]
    #[Assert\NotBlank(groups: ['Money'])]
    #[Assert\Positive(groups: ['Money'])]
    public int $amount;

    #[Property(example: 'EUR')]
    #[Assert\NotBlank(groups: ['Money'])]
    #[Assert\Currency(groups: ['Money'])]
    public string $currency;

    public static function free(): self
    {
        $payload = new self();
        $payload->amount = 0;
        $payload->currency = 'EUR';

        return $payload;
    }
}
