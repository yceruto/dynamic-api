<?php

namespace App\Product\Presentation\PathPublisher;

use App\Shared\Domain\Error\EndpointDisabledError;
use App\Shared\Presentation\OpenApi\Processor\Path\PathPublisher;

readonly class ProductPathPublisher implements PathPublisher
{
    public function __construct(private bool $featureToggle = false)
    {
    }

    public function publish(array $context): bool
    {
        if (!$this->featureToggle) {
            return false; //throw new EndpointDisabledError('Product delete endpoint is disabled');
        }

        return true;
    }
}
