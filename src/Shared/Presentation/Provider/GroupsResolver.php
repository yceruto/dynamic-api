<?php

namespace App\Shared\Presentation\Provider;

use App\Shared\Presentation\OpenApi\Attributes\Schema;

readonly class GroupsResolver
{
    public function __construct(private GroupsProviderContainer $groupsProviders)
    {
    }

    public function resolve(object $object): array
    {
        $schema = (new \ReflectionClass(get_class($object)))
            ->getAttributes(Schema::class, \ReflectionAttribute::IS_INSTANCEOF)[0] ?? null;

        if (null === $schema) {
            return [];
        }

        $attribute = $schema->newInstance();

        if (!$attribute instanceof Schema) {
            return [];
        }

        if (!$attribute->groupsProvider) {
            return [];
        }

        return $this->groupsProviders->get($attribute->groupsProvider)->groups($object);
    }
}
