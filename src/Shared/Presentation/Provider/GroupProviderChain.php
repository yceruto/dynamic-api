<?php

namespace App\Shared\Presentation\Provider;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

readonly class GroupProviderChain implements GroupProvider
{
    public function __construct(
        #[TaggedIterator('api.group_provider')]
        private iterable $providers
    ) {
    }

    public function groups(object $object): array
    {
        $groups = [['Default']];
        foreach ($this->providers as $provider) {
            $groups[] = $provider->groups($object);
        }

        return array_merge(...$groups);
    }
}
