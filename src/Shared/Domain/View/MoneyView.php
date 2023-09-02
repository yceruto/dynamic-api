<?php

namespace App\Shared\Domain\View;

use Money\Money;
use OpenApi\Attributes as OA;

#[OA\Schema]
readonly class MoneyView
{
    #[OA\Property(example: 100)]
    public int $amount;

    #[OA\Property(example: 'EUR')]
    public string $currency;

    public static function create(Money $money): self
    {
        return new self($money);
    }

    private function __construct(Money $money)
    {
        $this->amount = $money->getAmount();
        $this->currency = $money->getCurrency()->getCode();
    }
}
