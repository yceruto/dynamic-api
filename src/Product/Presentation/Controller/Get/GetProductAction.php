<?php

namespace App\Product\Presentation\Controller\Get;

use App\Product\Application\Find\One\FindProductQuery;
use App\Product\Domain\Decider\ProductFeatureDecider;
use App\Product\Domain\View\ProductView;
use App\Shared\Presentation\Controller\QueryAction;
use App\Shared\Presentation\OpenApi\Attributes\Path;
use App\Shared\Presentation\OpenApi\Routing\Attribute\Get;

class GetProductAction extends QueryAction
{
    #[Get(
        path: '/products/{id}',
        summary: 'Get a product by id',
        tags: ['Product'],
        decider:  ProductFeatureDecider::class,
    )]
    public function __invoke(#[Path] string $id): ProductView
    {
        return $this->queryBus()->ask(new FindProductQuery($id));
    }
}
