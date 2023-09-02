<?php

namespace App\Product\Application\Find\One;

use App\Shared\Domain\Bus\Query\Query;

readonly class FindProductQuery implements Query
{
    public function __construct(public string $id)
    {
    }
}
