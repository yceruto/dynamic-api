<?php

namespace App\Shared\Presentation\OpenApi\Processor\Publisher;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;

readonly class EndpointPublisherContainer implements ContainerInterface
{
    /**
     * @param ContainerInterface<string, EndpointPublisher> $publishers
     */
    public function __construct(#[TaggedLocator('api.endpoint_publisher')] private ContainerInterface $publishers)
    {
    }

    public function get(string $id): EndpointPublisher
    {
        return $this->publishers->get($id);
    }

    public function has(string $id): bool
    {
        return $this->publishers->has($id);
    }
}
