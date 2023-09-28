<?php

namespace App\Product\Presentation\Controller\Put;

use App\Product\Application\Update\UpdateProductCommand;
use App\Product\Domain\Decider\ProductFeatureDecider;
use App\Product\Domain\View\ProductView;
use App\Shared\Presentation\Controller\CommandAction;
use App\Shared\Presentation\OpenApi\Attributes\Path;
use App\Shared\Presentation\OpenApi\Attributes\Payload;
use App\Shared\Presentation\OpenApi\Routing\Attribute\Put;
use App\Shared\Presentation\Request\Money\MoneyPayload;
use Money\Currency;
use Money\Money;

class PutProductAction extends CommandAction
{
    #[Put(
        path: '/products/{id}',
        summary: 'Update a product',
        tags: ['Product'],
        decider:  ProductFeatureDecider::class,
    )]
    public function __invoke(#[Path(format: 'uuid')] string $id, #[Payload] PutProductPayload $payload): ProductView
    {
        $price = $payload->price ?? MoneyPayload::free();

        return $this->commandBus()->execute(new UpdateProductCommand(
            $id,
            $payload->name,
            new Money(
                $price->amount,
                new Currency($price->currency,
            )),
            $payload->status,
        ));
    }
}
