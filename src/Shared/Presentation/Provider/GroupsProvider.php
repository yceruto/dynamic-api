<?php

namespace App\Shared\Presentation\Provider;

interface GroupsProvider
{
    /**
     * @return string[]
     */
    public function groups(object $object): array;
}
