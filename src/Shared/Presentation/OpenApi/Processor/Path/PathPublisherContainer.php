<?php

namespace App\Shared\Presentation\OpenApi\Processor\Path;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedLocator;

readonly class PathPublisherContainer implements ContainerInterface
{
    /**
     * @param ContainerInterface<string, PathPublisher> $publishers
     */
    public function __construct(#[TaggedLocator('api.path_publisher')] private ContainerInterface $publishers)
    {
    }

    public function get(string $id): PathPublisher
    {
        return $this->publishers->get($id);
    }

    public function has(string $id): bool
    {
        return $this->publishers->has($id);
    }
}
