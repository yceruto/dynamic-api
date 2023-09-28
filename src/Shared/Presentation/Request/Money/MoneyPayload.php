<?php

namespace App\Shared\Presentation\Request\Money;

use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\OpenApi\Attributes\Schema;
use Symfony\Component\Validator\Constraints as Assert;

#[Schema]
class MoneyPayload
{
    #[Property(description: 'Amount in cents', example: 100, groups: ['Money'])]
    #[Assert\PositiveOrZero(groups: ['Money'])]
    public int $amount;

    #[Property(
        format: 'currency',
        maxLength: 3,
        minLength: 3,
        example: 'EUR',
        groups: ['Money'],
    )]
    public string $currency;

    public static function free(): self
    {
        $payload = new self();
        $payload->amount = 0;
        $payload->currency = 'EUR';

        return $payload;
    }
}
