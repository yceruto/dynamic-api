<?php

namespace App\Shared\Domain\Money\Decider;

use App\Shared\Presentation\Decider\FeatureDecider;

readonly class MoneyFeatureDecider implements FeatureDecider
{
    public function __construct(private bool $featureToggle = true)
    {
    }

    public function decide(array $context = []): bool
    {
        return $this->featureToggle;
    }
}
