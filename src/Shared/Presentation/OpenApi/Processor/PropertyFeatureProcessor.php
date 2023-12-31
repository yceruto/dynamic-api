<?php

namespace App\Shared\Presentation\OpenApi\Processor;

use App\Shared\Domain\Error\FeatureUnavailableError;
use App\Shared\Presentation\Decider\FeatureDeciderContainer;
use App\Shared\Presentation\OpenApi\Attributes\Property;
use OpenApi\Analysis;
use OpenApi\Processors\ProcessorInterface;

readonly class PropertyFeatureProcessor implements ProcessorInterface
{
    use ProcessorTrait;

    public function __construct(private FeatureDeciderContainer $deciders)
    {
    }

    public function __invoke(Analysis $analysis): void
    {
        foreach ($analysis->openapi->components->schemas as $schema) {
            foreach ($schema->properties as $i => $property) {
                if (!$property instanceof Property) {
                    continue;
                }

                if (null === $deciderId = $property->decider) {
                    continue;
                }

                try {
                    if (false === $this->deciders->get($deciderId)->decide(['subject' => $property])) {
                        throw new FeatureUnavailableError();
                    }
                } catch (FeatureUnavailableError) {
                    unset($schema->properties[$i]);
                    $this->detachAnnotationRecursively($property, $analysis);
                }
            }
        }
    }
}
