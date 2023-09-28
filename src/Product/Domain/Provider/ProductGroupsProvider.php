<?php

namespace App\Product\Domain\Provider;

use App\Shared\Domain\Money\Decider\MoneyFeatureDecider;
use App\Shared\Presentation\Provider\GroupsProvider;

readonly class ProductGroupsProvider implements GroupsProvider
{
    public function __construct(private MoneyFeatureDecider $decider)
    {
    }

    public function groups(object $object): array
    {
        if ($this->decider->decide(['subject' => $object])) {
            return ['Default', 'Money'];
        }

        return ['Default'];
    }
}
