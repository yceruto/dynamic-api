<?php

namespace App\Product\Presentation\Provider;

use App\Shared\Domain\View\MoneyAware;
use App\Shared\Presentation\Provider\GroupProvider;

readonly class MoneyFeatureGroupProvider implements GroupProvider
{
    public function __construct(private bool $featureToggle = true)
    {
    }

    public function groups(object $object): array
    {
        if (!$object instanceof MoneyAware || !$this->featureToggle) {
            return [];
        }

        return ['Money'];
    }
}
