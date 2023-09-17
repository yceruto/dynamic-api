<?php

namespace App\Shared\Presentation\Provider;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;

readonly class GroupsProviderContainer implements ContainerInterface
{
    /**
     * @param ContainerInterface<class-string, GroupsProvider> $providers
     */
    public function __construct(#[TaggedLocator('api.groups_provider')] private ContainerInterface $providers)
    {
    }

    public function get(string $id): GroupsProvider
    {
        return $this->providers->get($id);
    }

    public function has(string $id): bool
    {
        return $this->providers->has($id);
    }
}
