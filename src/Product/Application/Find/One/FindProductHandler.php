<?php

namespace App\Product\Application\Find\One;

use App\Product\Application\Find\ProductFinder;
use App\Product\Domain\Model\ProductId;
use App\Product\Domain\View\ProductView;
use App\Shared\Domain\Bus\Query\QueryHandler;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('query_bus.handler', ['query' => FindProductQuery::class])]
readonly class FindProductHandler implements QueryHandler
{
    public function __construct(private ProductFinder $finder)
    {
    }

    public function __invoke(FindProductQuery $query): mixed
    {
        $product = $this->finder->find(ProductId::fromString($query->id));

        return ProductView::create($product);
    }
}
