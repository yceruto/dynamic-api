<?php

namespace App\Product\Presentation\Publisher;

use App\Shared\Domain\Error\EndpointDisabledError;
use App\Shared\Presentation\OpenApi\Processor\Publisher\EndpointPublisher;

readonly class ProductEndpointPublisher implements EndpointPublisher
{
    public function __construct(private bool $featureToggle = true)
    {
    }

    public function publish(array $context): bool
    {
        if (!$this->featureToggle) {
            return false; //throw new EndpointDisabledError('Product endpoint is disabled');
        }

        return true;
    }
}
