<?php

namespace App\Shared\Presentation\Serializer;

use Symfony\Component\Validator\Constraints\GroupSequence;

interface GroupProvider
{
    /**
     * @return string[]|GroupSequence|GroupSequence[]
     */
    public function groups(object $object): array|GroupSequence;
}
