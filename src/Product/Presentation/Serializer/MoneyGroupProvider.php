<?php

namespace App\Product\Presentation\Serializer;

use App\Shared\Domain\View\MoneyAware;
use App\Shared\Presentation\Serializer\GroupProvider;
use Symfony\Component\Validator\Constraints\GroupSequence;

class MoneyGroupProvider implements GroupProvider
{
    public function groups(object $object): array|GroupSequence
    {
        if (!$object instanceof MoneyAware) {
            return [];
        }

        return ['money'];
    }
}
