<?php

namespace App\Product\Presentation\Controller\Post;

use App\Product\Application\Create\CreateProductCommand;
use App\Product\Domain\View\ProductView;
use App\Shared\Presentation\Controller\CommandAction;
use App\Shared\Presentation\Request\Attribute\Payload;
use App\Shared\Presentation\Routing\Attribute\Post;
use Money\Currency;
use Money\Money;
use Symfony\Component\Uid\Uuid;

class PostProductAction extends CommandAction
{
    #[Post(
        path: '/products',
        summary: 'Create a new product',
        tags: ['Product'],
    )]
    public function __invoke(#[Payload] PostProductPayload $payload): ProductView
    {
        return $this->commandBus()->execute(new CreateProductCommand(
            $payload->id ?? Uuid::v4()->toRfc4122(),
            $payload->name,
            new Money(
                $payload->price->amount,
                new Currency($payload->price->currency,
            )),
        ));
    }
}
