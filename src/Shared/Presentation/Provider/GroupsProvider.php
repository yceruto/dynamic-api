<?php

namespace App\Shared\Presentation\Provider;

/**
 * Determines the validation & serializer groups for a given object.
 */
interface GroupsProvider
{
    /**
     * @return string[]
     */
    public function groups(object $object): array;
}
