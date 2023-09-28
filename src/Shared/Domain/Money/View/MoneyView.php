<?php

namespace App\Shared\Domain\Money\View;

use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\OpenApi\Attributes\Schema;
use Money\Money;

#[Schema]
readonly class MoneyView
{
    public static function fromMoney(Money $money): self
    {
        return new self($money->getAmount(), $money->getCurrency()->getCode());
    }

    private function __construct(
        #[Property(
            description: 'Amount in cents',
            minimum: 0,
            example: 100,
            groups: ['Money'],
        )]
        public int $amount,

        #[Property(example: 'EUR', groups: ['Money'])]
        public string $currency,
    ) {
    }
}
