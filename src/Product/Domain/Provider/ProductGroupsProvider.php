<?php

namespace App\Product\Domain\Provider;

use App\Shared\Presentation\Provider\GroupsProvider;

readonly class ProductGroupsProvider implements GroupsProvider
{
    public function __construct(private bool $featureToggle = false)
    {
    }

    public function groups(object $object): array
    {
        if (!$this->featureToggle) {
            return ['Default'];
        }

        return ['Default', 'Money'];
    }
}
