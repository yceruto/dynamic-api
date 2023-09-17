<?php

namespace App\Shared\Presentation\OpenApi\Processor\Publisher;

/**
 * Determines whether a path should be published or not.
 * If not, the path will not be added to the OpenApi specification and
 * the endpoint will not be available.
 */
interface EndpointPublisher
{
    public function publish(array $context): bool;
}
