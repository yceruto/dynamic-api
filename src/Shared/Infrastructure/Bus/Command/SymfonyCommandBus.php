<?php

namespace App\Shared\Infrastructure\Bus\Command;

use App\Product\Application\Create\CreateProductHandler;
use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Command\CommandBus;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;

readonly class SymfonyCommandBus implements CommandBus
{
    public function __construct(
        #[TaggedLocator(tag: 'command_bus.handler', indexAttribute: 'command')] private ContainerInterface $handlers
    ) {
    }

    public function execute(Command $command): mixed
    {
        return $this->handlers->get(get_class($command))->__invoke($command);
    }
}
