<?php

namespace App\Product\Domain\Publisher;

use App\Shared\Presentation\OpenApi\Processor\Publisher\FeaturePublisher;

readonly class ProductFeaturePublisher implements FeaturePublisher
{
    public function __construct(private bool $featureToggle = true)
    {
    }

    public function publish(array $context = []): bool
    {
        if (!$this->featureToggle) {
            return false; //throw new EndpointDisabledError('Product feature is disabled');
        }

        return true;
    }
}
