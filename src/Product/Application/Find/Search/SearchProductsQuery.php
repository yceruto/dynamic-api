<?php

namespace App\Product\Application\Find\Search;

use App\Shared\Domain\Bus\Query\Query;

readonly class SearchProductsQuery implements Query
{
    public function __construct(
        public int $limit,
        public int $offset,
    ) {
    }
}
