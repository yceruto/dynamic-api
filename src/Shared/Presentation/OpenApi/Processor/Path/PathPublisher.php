<?php

namespace App\Shared\Presentation\OpenApi\Processor\Path;

interface PathPublisher
{
    public function publish(string $pathId, array $context): void;
}
