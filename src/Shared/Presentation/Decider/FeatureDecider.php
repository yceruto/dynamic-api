<?php

namespace App\Shared\Presentation\Decider;

/**
 * Determines whether a feature should be published or not.
 */
interface FeatureDecider
{
    public function publish(array $context = []): bool;
}
