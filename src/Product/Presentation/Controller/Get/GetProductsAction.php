<?php

namespace App\Product\Presentation\Controller\Get;

use App\Product\Application\Find\Search\SearchProductsQuery;
use App\Product\Domain\Publisher\ProductFeaturePublisher;
use App\Product\Domain\View\ProductView;
use App\Shared\Presentation\Controller\QueryAction;
use App\Shared\Presentation\OpenApi\Routing\Attribute\Get;
use App\Shared\Presentation\Request\Attribute\Query;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;

class GetProductsAction extends QueryAction
{
    #[Get(
        path: '/products',
        summary: 'Get all products',
        tags: ['Product'],
        responses: [
            new Response(
                response: 200,
                description: 'Products list',
                content: new JsonContent(
                    type: 'array',
                    items: new Items(type: ProductView::class),
                ),
            )
        ],
        publisher:  ProductFeaturePublisher::class,
    )]
    public function __invoke(#[Query] GetProductsFilter $filter): array
    {
        return $this->queryBus()->ask(new SearchProductsQuery(
            $filter->limit,
            $filter->offset,
        ));
    }
}
