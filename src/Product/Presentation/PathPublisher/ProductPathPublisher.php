<?php

namespace App\Product\Presentation\PathPublisher;

use App\Shared\Presentation\OpenApi\Processor\Path\PathPublisher;

readonly class ProductPathPublisher implements PathPublisher
{
    public function __construct(private bool $featureToggle = true)
    {
    }

    public function publish(string $pathId, array $context): bool
    {
        return $this->featureToggle && 'product_delete' !== $pathId;
    }
}
