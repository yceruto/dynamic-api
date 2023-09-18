<?php

namespace App\Shared\Domain\Publisher;

use App\Shared\Presentation\OpenApi\Processor\Publisher\FeaturePublisher;

readonly class MoneyFeaturePublisher implements FeaturePublisher
{
    public function __construct(private bool $featureToggle = true)
    {
    }

    public function publish(array $context = []): bool
    {
        return $this->featureToggle;
    }
}
