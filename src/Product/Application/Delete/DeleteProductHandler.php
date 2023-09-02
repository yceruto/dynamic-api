<?php

namespace App\Product\Application\Delete;

use App\Product\Domain\Model\ProductId;
use App\Shared\Domain\Bus\Command\CommandHandler;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('command_bus.handler', ['command' => DeleteProductCommand::class])]
readonly class DeleteProductHandler implements CommandHandler
{
    public function __construct(private ProductDeleter $deleter)
    {
    }

    public function __invoke(DeleteProductCommand $command): void
    {
        $this->deleter->delete(ProductId::fromString($command->id));
    }
}
