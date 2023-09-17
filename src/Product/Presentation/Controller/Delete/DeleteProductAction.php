<?php

namespace App\Product\Presentation\Controller\Delete;

use App\Product\Application\Delete\DeleteProductCommand;
use App\Product\Presentation\Publisher\ProductEndpointPublisher;
use App\Shared\Presentation\Controller\CommandAction;
use App\Shared\Presentation\Request\Attribute\Path;
use App\Shared\Presentation\Routing\Attribute\Delete;

class DeleteProductAction extends CommandAction
{
    #[Delete(
        path: '/products/{id}',
        summary: 'Delete a product',
        tags: ['Product'],
        publisher:  ProductEndpointPublisher::class,
    )]
    public function __invoke(#[Path] string $id): void
    {
        $this->commandBus()->execute(new DeleteProductCommand($id));
    }
}
