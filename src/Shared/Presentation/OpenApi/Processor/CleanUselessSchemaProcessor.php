<?php

namespace App\Shared\Presentation\OpenApi\Processor;

use OpenApi\Analysis;
use OpenApi\Processors\ProcessorInterface;

class CleanUselessSchemaProcessor implements ProcessorInterface
{
    public function __invoke(Analysis $analysis): void
    {
        foreach ($analysis->openapi->components->schemas as $i => $schema) {
            foreach ($analysis->annotations as $annotation) {
                if (property_exists($annotation, 'ref') && (string) $annotation->ref === '#/components/schemas/'.$schema->schema) {
                    continue 2;
                }
            }

            unset($analysis->openapi->components->schemas[$i]);
        }
    }
}
