<?php

namespace App\Product\Domain\Decider;

use App\Shared\Domain\Error\FeatureUnavailableError;
use App\Shared\Presentation\Decider\FeatureDecider;

readonly class ProductFeatureDecider implements FeatureDecider
{
    public function __construct(private bool $featureToggle = true)
    {
    }

    public function decide(array $context = []): bool
    {
        if (!$this->featureToggle) {
            return false; //throw FeatureUnavailableError::create('Product feature is disabled');
        }

        return true;
    }
}
