<?php

namespace App\Shared\Presentation\OpenApi\Processor\Publisher;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;

readonly class FeaturePublisherContainer implements ContainerInterface
{
    /**
     * @param ContainerInterface<string, FeaturePublisher> $publishers
     */
    public function __construct(#[TaggedLocator('api.endpoint_publisher')] private ContainerInterface $publishers)
    {
    }

    public function get(string $id): FeaturePublisher
    {
        return $this->publishers->get($id);
    }

    public function has(string $id): bool
    {
        return $this->publishers->has($id);
    }
}
