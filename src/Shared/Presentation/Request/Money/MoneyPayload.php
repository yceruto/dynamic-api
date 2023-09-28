<?php

namespace App\Shared\Presentation\Request\Money;

use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\OpenApi\Attributes\Schema;

#[Schema]
class MoneyPayload
{
    #[Property(
        description: 'Amount in cents',
        minimum: 0,
        example: 100,
        groups: ['Money'],
    )]
    public int $amount;

    #[Property(
        format: 'currency',
        maxLength: 3,
        minLength: 3,
        example: 'EUR',
        groups: ['Money'],
    )]
    public string $currency;

    public static function free(string $currency = 'EUR'): self
    {
        $payload = new self();
        $payload->amount = 0;
        $payload->currency = $currency;

        return $payload;
    }
}
