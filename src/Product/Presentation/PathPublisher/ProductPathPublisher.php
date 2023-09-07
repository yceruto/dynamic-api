<?php

namespace App\Product\Presentation\PathPublisher;

use App\Shared\Presentation\OpenApi\Processor\Path\PathPublisher;

class ProductPathPublisher implements PathPublisher
{
    public function publish(string $pathId, array $context): bool
    {
        return 'product_get' !== $pathId;
    }
}
