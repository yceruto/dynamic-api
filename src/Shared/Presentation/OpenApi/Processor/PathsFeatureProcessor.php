<?php

namespace App\Shared\Presentation\OpenApi\Processor;

use App\Shared\Domain\Error\FeatureDisabledError;
use App\Shared\Presentation\Decider\FeatureDeciderContainer;
use App\Shared\Presentation\OpenApi\Routing\Attribute\ApiRouteTrait;
use OpenApi\Analysis;
use OpenApi\Annotations\Operation;
use OpenApi\Generator;
use OpenApi\Processors\ProcessorInterface;

readonly class PathsFeatureProcessor implements ProcessorInterface
{
    use ProcessorTrait;

    public function __construct(private FeatureDeciderContainer $deciders)
    {
    }

    public function __invoke(Analysis $analysis): void
    {
        foreach ($analysis->openapi->paths as $index => $pathItem) {
            /** @var Operation[]|ApiRouteTrait[] $methods */
            $methods = [
                $pathItem->post,
                $pathItem->get,
                $pathItem->put,
                $pathItem->patch,
                $pathItem->delete,
            ];

            foreach ($methods as $method) {
                if (Generator::isDefault($method) || Generator::isDefault($method->operationId)) {
                    continue;
                }

                if (!$deciderId = $method->route->getDefaults()['_decider'] ?? '') {
                    continue;
                }

                $featureDecider = $this->deciders->get($deciderId);

                try {
                    if (!$featureDecider->decide([
                        'path_id' => $method->operationId,
                        'path_item' => $pathItem,
                        'subject' => $method,
                    ])) {
                        throw new FeatureDisabledError();
                    }
                } catch (FeatureDisabledError) {
                    $analysis->openapi->paths[$index]->{$method->method} = Generator::UNDEFINED;
                    $this->detachAnnotationRecursively($method, $analysis);
                }
            }

            if (Generator::isDefault($pathItem->post)
                && Generator::isDefault($pathItem->get)
                && Generator::isDefault($pathItem->put)
                && Generator::isDefault($pathItem->patch)
                && Generator::isDefault($pathItem->delete)) {
                unset($analysis->openapi->paths[$index]);
                $this->detachAnnotationRecursively($pathItem, $analysis);
            }
        }
    }
}
