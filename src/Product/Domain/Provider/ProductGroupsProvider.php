<?php

namespace App\Product\Domain\Provider;

use App\Shared\Domain\Publisher\MoneyFeaturePublisher;
use App\Shared\Presentation\Provider\GroupsProvider;

readonly class ProductGroupsProvider implements GroupsProvider
{
    public function __construct(private MoneyFeaturePublisher $publisher)
    {
    }

    public function groups(object $object): array
    {
        if (!$this->publisher->publish()) {
            return ['Default'];
        }

        return ['Default', 'Money'];
    }
}
