<?php

namespace App\Shared\Presentation\OpenApi\Processor\Path;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

readonly class PathPublisherChain implements PathPublisher
{
    /**
     * @param iterable<PathPublisher> $publishers
     */
    public function __construct(#[TaggedIterator('api.path_publisher')] private iterable $publishers)
    {
    }

    public function publish(string $pathId, array $context): void
    {
        foreach ($this->publishers as $publisher) {
            $publisher->publish($pathId, $context);
        }
    }
}
