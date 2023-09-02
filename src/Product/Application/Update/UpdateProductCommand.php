<?php

namespace App\Product\Application\Update;

use App\Shared\Domain\Bus\Command\Command;
use Money\Money;

readonly class UpdateProductCommand implements Command
{
    public function __construct(
        public string $id,
        public string $name,
        public Money $price,
    ) {
    }
}
