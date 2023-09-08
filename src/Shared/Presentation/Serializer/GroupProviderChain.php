<?php

namespace App\Shared\Presentation\Serializer;

use DomainException;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Validator\Constraints\GroupSequence;

readonly class GroupProviderChain implements GroupProvider
{
    public function __construct(
        #[TaggedIterator('api.group_provider')]
        private iterable $providers
    ) {
    }

    public function groups(object $object): array|GroupSequence
    {
        $groups = [];
        foreach ($this->providers as $provider) {
            $groups[] = $provider->groups($object);
        }

        return array_merge(...$groups);
    }
}
