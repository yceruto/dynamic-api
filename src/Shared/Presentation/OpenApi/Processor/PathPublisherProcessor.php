<?php

namespace App\Shared\Presentation\OpenApi\Processor;

use App\Shared\Presentation\OpenApi\Processor\Path\PathPublisher;
use OpenApi\Analysis;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\PathItem;
use OpenApi\Generator;
use OpenApi\Processors\ProcessorInterface;

readonly class PathPublisherProcessor implements ProcessorInterface
{
    public function __construct(private PathPublisher $publisher)
    {
    }

    public function __invoke(Analysis $analysis): void
    {
        /** @var PathItem[] $paths */
        $pathItems = $analysis->openapi->paths;

        foreach ($pathItems as $index => $pathItem) {
            /** @var Operation[] $methods */
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

                $context = ['path_item' => $pathItem, 'method' => $method];
                if (!$this->publisher->publish($method->operationId, $context)) {
                    $analysis->openapi->paths[$index]->{$method->method} = Generator::UNDEFINED;
                    $analysis->annotations->detach($pathItem);
                }
            }
        }
    }
}
