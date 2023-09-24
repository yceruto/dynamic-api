<?php

namespace App\Shared\Presentation\Request\Model;

use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\OpenApi\Attributes\Schema;
use Symfony\Component\Validator\Constraints as Assert;

#[Schema]
class MoneyPayload
{
    #[Property(description: 'Amount in cents', example: 100, groups: ['Money'])]
    #[Assert\PositiveOrZero(groups: ['Money'])]
    public int $amount;

    #[Property(example: 'EUR', groups: ['Money'])]
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
