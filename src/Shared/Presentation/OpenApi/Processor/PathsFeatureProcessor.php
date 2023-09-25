<?php

namespace App\Shared\Presentation\OpenApi\Processor;

use App\Shared\Domain\Error\FeatureDisabledError;
use App\Shared\Presentation\OpenApi\Routing\Attribute\ApiRouteTrait;
use App\Shared\Presentation\Publisher\FeaturePublisherContainer;
use OpenApi\Analysis;
use OpenApi\Annotations\Operation;
use OpenApi\Generator;
use OpenApi\Processors\ProcessorInterface;

readonly class PathsFeatureProcessor implements ProcessorInterface
{
    use ProcessorTrait;

    public function __construct(private FeaturePublisherContainer $publishers)
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

                if (!$publisherId = $method->route->getDefaults()['_publisher'] ?? '') {
                    continue;
                }

                $featurePublisher = $this->publishers->get($publisherId);

                try {
                    if (!$featurePublisher->publish([
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
                $analysis->annotations->detach($pathItem);
            }
        }
    }
}
