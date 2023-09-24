<?php

namespace App\Product\Application\Create;

use App\Shared\Domain\Bus\Command\Command;
use Money\Money;

readonly class CreateProductCommand implements Command
{
    public function __construct(
        public string $id,
        public string $name,
        public Money $price,
        public string $status,
    ) {
    }
}
