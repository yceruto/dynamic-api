<?php

namespace App\Shared\Presentation\OpenApi\Analyser;

use OpenApi\Annotations\AbstractAnnotation;
use OpenApi\Context;

interface AttributeFactory
{
    /**
     * @param array<AbstractAnnotation> $annotations
     *
     * @return array<AbstractAnnotation>
     */
    public function build(\Reflector $reflector, array $annotations, Context $context): array;
}
