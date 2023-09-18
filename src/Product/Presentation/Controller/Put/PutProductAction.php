<?php

namespace App\Product\Presentation\Controller\Put;

use App\Product\Application\Update\UpdateProductCommand;
use App\Product\Domain\Publisher\ProductFeaturePublisher;
use App\Product\Domain\View\ProductView;
use App\Shared\Presentation\Controller\CommandAction;
use App\Shared\Presentation\Request\Attribute\Path;
use App\Shared\Presentation\Request\Attribute\Payload;
use App\Shared\Presentation\Routing\Attribute\Put;
use Money\Currency;
use Money\Money;

class PutProductAction extends CommandAction
{
    #[Put(
        path: '/products/{id}',
        summary: 'Update a product',
        tags: ['Product'],
        publisher:  ProductFeaturePublisher::class,
    )]
    public function __invoke(#[Path] string $id, #[Payload] PutProductPayload $payload): ProductView
    {
        return $this->commandBus()->execute(new UpdateProductCommand(
            $id,
            $payload->name,
            new Money(
                $payload->price->amount,
                new Currency($payload->price->currency,
            )),
        ));
    }
}
