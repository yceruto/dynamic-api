<?php

namespace App\Shared\Presentation\OpenApi\Processor;

use App\Shared\Domain\Error\EndpointDisabledError;
use App\Shared\Presentation\OpenApi\Processor\Path\PathPublisherContainer;
use App\Shared\Presentation\Routing\Attribute\ApiRouteTrait;
use OpenApi\Analysis;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\PathItem;
use OpenApi\Generator;
use OpenApi\Processors\ProcessorInterface;

readonly class PathPublisherProcessor implements ProcessorInterface
{
    public function __construct(private PathPublisherContainer $publishers)
    {
    }

    public function __invoke(Analysis $analysis): void
    {
        /** @var PathItem[] $paths */
        $pathItems = $analysis->openapi->paths;

        foreach ($pathItems as $index => $pathItem) {
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

                if ('' === $publisherId = $method->route->getDefaults()['_publisher'] ?? '') {
                    continue;
                }

                $pathPublisher = $this->publishers->get($publisherId);

                try {
                    if (!$pathPublisher->publish(['path_item' => $pathItem, 'method' => $method])) {
                        throw new EndpointDisabledError();
                    }
                } catch (EndpointDisabledError) {
                    $analysis->openapi->paths[$index]->{$method->method} = Generator::UNDEFINED;
                    $analysis->annotations->detach($pathItem);
                }
            }
        }
    }
}
