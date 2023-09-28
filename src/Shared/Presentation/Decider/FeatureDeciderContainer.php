<?php

namespace App\Shared\Presentation\Decider;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;

readonly class FeatureDeciderContainer implements ContainerInterface
{
    /**
     * @param ContainerInterface<string, FeatureDecider> $deciders
     */
    public function __construct(#[TaggedLocator('api.feature_decider')] private ContainerInterface $deciders)
    {
    }

    public function get(string $id): FeatureDecider
    {
        return $this->deciders->get($id);
    }

    public function has(string $id): bool
    {
        return $this->deciders->has($id);
    }
}
