<?php

namespace App\Product\Application\Create;

use App\Product\Domain\Model\ProductId;
use App\Product\Domain\Model\ProductStatus;
use App\Product\Domain\View\ProductView;
use App\Shared\Domain\Bus\Command\CommandHandler;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('command_bus.handler', ['command' => CreateProductCommand::class])]
readonly class CreateProductHandler implements CommandHandler
{
    public function __construct(private ProductCreator $creator)
    {
    }

    public function __invoke(CreateProductCommand $command): ProductView
    {
        $product = $this->creator->create(
            ProductId::fromString($command->id),
            $command->name,
            $command->price,
            ProductStatus::from($command->status),
        );

        return ProductView::create($product);
    }
}
