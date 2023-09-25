<?php

namespace App\Shared\Presentation\OpenApi\Processor;

use App\Shared\Domain\Error\FeatureDisabledError;
use App\Shared\Presentation\OpenApi\Attributes\Property;
use App\Shared\Presentation\Publisher\FeaturePublisherContainer;
use OpenApi\Analysis;
use OpenApi\Generator;
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
                if (!$property instanceof Property) {
                    continue;
                }

                if (null === $publisherId = $property->publisher) {
                    continue;
                }

                try {
                    if (!$this->publishers->get($publisherId)->publish(['subject' => $property])) {
                        throw new FeatureDisabledError();
                    }
                } catch (FeatureDisabledError) {
                    unset($schema->properties[$i]);
                    $analysis->annotations->detach($property);

                    if (!Generator::isDefault($property->oneOf)) {
                        foreach ($property->oneOf as $oneOf) {
                            $analysis->annotations->detach($oneOf);
                        }
                    }
                }
            }
        }
    }
}
