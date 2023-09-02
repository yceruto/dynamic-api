<?php

namespace App\Product\Application\Find\Search;

use App\Product\Application\Find\ProductFinder;
use App\Product\Domain\View\ProductView;
use App\Shared\Domain\Bus\Query\QueryHandler;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('query_bus.handler', ['query' => SearchProductsQuery::class])]
readonly class SearchProductsHandler implements QueryHandler
{
    public function __construct(private ProductFinder $finder)
    {
    }

    public function __invoke(SearchProductsQuery $query): array
    {
        $products = $this->finder->search();

        return ProductView::createMany($products);
    }
}
