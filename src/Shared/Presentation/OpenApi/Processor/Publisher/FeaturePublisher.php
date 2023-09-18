<?php

namespace App\Shared\Presentation\OpenApi\Processor\Publisher;

/**
 * Determines whether a feature should be published or not.
 */
interface FeaturePublisher
{
    public function publish(array $context = []): bool;
}
