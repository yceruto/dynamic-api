<?php

namespace App\Product\Presentation\Controller\Patch;

use App\Product\Application\Find\One\FindProductQuery;
use App\Product\Application\Update\UpdateProductCommand;
use App\Product\Domain\Publisher\ProductFeaturePublisher;
use App\Product\Domain\View\ProductView;
use App\Shared\Presentation\Controller\ApiAction;
use App\Shared\Presentation\Request\Attribute\Path;
use App\Shared\Presentation\Request\Attribute\Payload;
use App\Shared\Presentation\Routing\Attribute\Patch;
use Money\Currency;
use Money\Money;

class PatchProductAction extends ApiAction
{
    #[Patch(
        path: '/products/{id}',
        summary: 'Patch a product',
        tags: ['Product'],
        publisher:  ProductFeaturePublisher::class,
    )]
    public function __invoke(#[Path] string $id, #[Payload] PatchProductPayload $payload): ProductView
    {
        /** @var ProductView $product */
        $product = $this->queryBus()->ask(new FindProductQuery($id));
        $price = $payload->price ?? $product->price;

        return $this->commandBus()->execute(new UpdateProductCommand(
            $id,
            $payload->name ?? $product->name,
            new Money(
                $price->amount,
                new Currency($price->currency),
            ),
            $payload->status ?? $product->status->value,
        ));
    }
}
