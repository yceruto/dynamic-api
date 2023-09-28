<?php

namespace App\Product\Presentation\Controller\Delete;

use App\Product\Application\Delete\DeleteProductCommand;
use App\Product\Domain\Decider\ProductFeatureDecider;
use App\Shared\Presentation\Controller\CommandAction;
use App\Shared\Presentation\OpenApi\Attributes\Path;
use App\Shared\Presentation\OpenApi\Routing\Attribute\Delete;

class DeleteProductAction extends CommandAction
{
    #[Delete(
        path: '/products/{id}',
        summary: 'Delete a product',
        tags: ['Product'],
        decider:  ProductFeatureDecider::class,
    )]
    public function __invoke(#[Path] string $id): void
    {
        $this->commandBus()->execute(new DeleteProductCommand($id));
    }
}
