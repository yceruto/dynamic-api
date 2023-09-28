<?php

namespace App\Shared\Presentation\Decider;

/**
 * Decides whether a feature should be available or not.
 */
interface FeatureDecider
{
    public function decide(array $context = []): bool;
}
