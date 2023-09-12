<?php

namespace App\Shared\Presentation\Provider;

interface GroupProvider
{
    /**
     * @return string[]
     */
    public function groups(object $object): array;
}
