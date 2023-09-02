<?php

namespace App\Shared\Infrastructure\Bus\Query;

use App\Shared\Domain\Bus\Query\Query;
use App\Shared\Domain\Bus\Query\QueryBus;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;

readonly class SymfonyQueryBus implements QueryBus
{
    public function __construct(
        #[TaggedLocator(tag: 'query_bus.handler', indexAttribute: 'query')] private ContainerInterface $handlers
    ) {
    }

    public function ask(Query $query): mixed
    {
        return $this->handlers->get(get_class($query))->__invoke($query);
    }
}
