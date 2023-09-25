<?php

namespace App\Shared\Presentation\OpenApi;

use OpenApi\Analysers;
use OpenApi\Annotations as OA;
use OpenApi\Processors;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

readonly class Generator
{
    /**
     * @param array<Analysers\AnnotationFactoryInterface> $factories
     * @param array<Processors\ProcessorInterface> $processors
     */
    public function __construct(
        #[TaggedIterator('api.annotation_factory')]
        private iterable $factories,
        #[TaggedIterator('api.processor', defaultPriorityMethod: 'priority')]
        private iterable $processors,
    ) {
    }

    /**
     * @param string[] $scanDirs
     */
    public function generate(array $scanDirs): ?OA\OpenApi
    {
        return \OpenApi\Generator::scan($scanDirs, [
            'analyser' => new Analysers\ReflectionAnalyser(array_merge([
                new Analysers\DocBlockAnnotationFactory(),
            ], iterator_to_array($this->factories))),
            'processors' => array_merge([
                new Processors\DocBlockDescriptions(),
                new Processors\MergeIntoOpenApi(),
                new Processors\MergeIntoComponents(),
                new Processors\ExpandClasses(),
                new Processors\ExpandInterfaces(),
                new Processors\ExpandTraits(),
                new Processors\ExpandEnums(),
                new Processors\AugmentSchemas(),
                new Processors\AugmentProperties(),
                new Processors\BuildPaths(),
                new Processors\AugmentParameters(),
                new Processors\AugmentRefs(),
                new Processors\MergeJsonContent(),
                new Processors\MergeXmlContent(),
                new Processors\OperationId(),
                new Processors\CleanUnmerged(),
            ], iterator_to_array($this->processors)),
        ]);
    }
}
