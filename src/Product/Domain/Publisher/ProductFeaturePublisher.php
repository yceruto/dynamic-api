<?php

namespace App\Product\Domain\Publisher;

use App\Shared\Domain\Error\FeatureDisabledError;
use App\Shared\Presentation\Publisher\FeaturePublisher;

readonly class ProductFeaturePublisher implements FeaturePublisher
{
    public function __construct(private bool $featureToggle = true)
    {
    }

    public function publish(array $context = []): bool
    {
        if (!$this->featureToggle) {
            return false; //throw new FeatureDisabledError('Product feature is disabled');
        }

        return true;
    }
}
