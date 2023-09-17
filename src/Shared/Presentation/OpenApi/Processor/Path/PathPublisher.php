<?php

namespace App\Shared\Presentation\OpenApi\Processor\Path;

/**
 * Determines whether a path should be published or not.
 * If not, the path will not be added to the OpenApi specification and
 * the endpoint will not be available.
 */
interface PathPublisher
{
    public function publish(array $context): bool;
}
