<?php

namespace App\Shared\Domain\View;

use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\OpenApi\Attributes\Schema;
use Money\Money;
use Symfony\Component\Serializer\Annotation\Groups;

#[Schema]
readonly class MoneyView
{
    #[Property(example: 100, groups: ['Money'])]
    public int $amount;

    #[Property(example: 'EUR', groups: ['Money'])]
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
