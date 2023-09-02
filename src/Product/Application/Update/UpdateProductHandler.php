<?php

namespace App\Product\Application\Update;

use App\Product\Domain\Model\ProductId;
use App\Product\Domain\View\ProductView;
use App\Shared\Domain\Bus\Command\CommandHandler;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('command_bus.handler', ['command' => UpdateProductCommand::class])]
readonly class UpdateProductHandler implements CommandHandler
{
    public function __construct(private ProductUpdater $updater)
    {
    }

    public function __invoke(UpdateProductCommand $command): ProductView
    {
        $product = $this->updater->update(
            ProductId::fromString($command->id),
            $command->name,
            $command->price,
        );

        return ProductView::create($product);
    }
}
