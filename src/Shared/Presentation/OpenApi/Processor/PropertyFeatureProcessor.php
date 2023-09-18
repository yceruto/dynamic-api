<?php

namespace App\Shared\Presentation\OpenApi\Processor;

use App\Shared\Domain\Error\FeatureDisabledError;
use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\OpenApi\Processor\Publisher\FeaturePublisherContainer;
use OpenApi\Analysis;
use OpenApi\Processors\ProcessorInterface;

readonly class PropertyFeatureProcessor implements ProcessorInterface
{
    public function __construct(private FeaturePublisherContainer $publishers)
    {
    }

    public function __invoke(Analysis $analysis): void
    {
        foreach ($analysis->openapi->components->schemas as $schema) {
            foreach ($schema->properties as $i => $property) {
                $annotation = $property->_context->annotations[0];

                if (!$annotation instanceof Property) {
                    continue;
                }

                if (null === $publisherId = $annotation->publisher) {
                    continue;
                }

                try {
                    if (!$this->publishers->get($publisherId)->publish([])) {
                        throw new FeatureDisabledError();
                    }
                } catch (FeatureDisabledError) {
                    unset($schema->properties[$i]);
                }
            }
        }
    }
}
