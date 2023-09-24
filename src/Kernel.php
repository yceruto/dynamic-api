<?php

namespace App;

use App\Shared\Presentation\OpenApi\Analyser\AttributeFactoryChain;
use App\Shared\Presentation\OpenApi\Processor\CleanUselessSchemaProcessor;
use App\Shared\Presentation\OpenApi\Processor\PathsFeatureProcessor;
use App\Shared\Presentation\OpenApi\Processor\PropertyFeatureProcessor;
use App\Shared\Presentation\OpenApi\Serializer\Mapping\Loader\OpenApiSerializerAttributeLoader;
use App\Shared\Presentation\OpenApi\Validator\Mapping\Loader\OpenApiValidatorAttributeLoader;
use OpenApi\Analysers\DocBlockAnnotationFactory;
use OpenApi\Analysers\ReflectionAnalyser;
use OpenApi\Attributes as OA;
use OpenApi\Generator;
use OpenApi\Processors;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Info(version: '1.0', title: 'Catalog API')]
class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    #[Route('/')]
    public function index(): Response
    {
        return new Response(file_get_contents(dirname(__DIR__).'/templates/swagger/index.html'));
    }

    #[Route('/swagger.json')]
    public function swagger(
        Request $request,
        AttributeFactoryChain $attributeFactoryChain,
        PathsFeatureProcessor $pathsFeatureProcessor,
        PropertyFeatureProcessor $propertyFeatureProcessor,
        CleanUselessSchemaProcessor $cleanUselessSchemaProcessor,
    ): JsonResponse {
        $openApi = Generator::scan([__DIR__], [
            'analyser' => new ReflectionAnalyser([
                new DocBlockAnnotationFactory(),
                $attributeFactoryChain,
            ]),
            'processors' => [
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
                $pathsFeatureProcessor,
                $propertyFeatureProcessor,
                $cleanUselessSchemaProcessor,
            ],
        ]) ?? throw new NotFoundHttpException();

        $openApi->servers = [
            new OA\Server(url: $request->getSchemeAndHttpHost()),
        ];

        return new JsonResponse($openApi->toJson(), json: true);
    }

    public function process(ContainerBuilder $container): void
    {
        $chainLoader = $container->getDefinition('serializer.mapping.chain_loader');
        $serializerLoaders = $chainLoader->getArgument(0);
        $serializerLoaders[] = new Reference(OpenApiSerializerAttributeLoader::class);
        $chainLoader->replaceArgument(0, $serializerLoaders);
        $container->getDefinition('serializer.mapping.cache_warmer')->replaceArgument(0, $serializerLoaders);

        $validatorBuilder = $container->getDefinition('validator.builder');
        $validatorBuilder->addMethodCall('addLoader', [new Reference(OpenApiValidatorAttributeLoader::class)]);
    }
}
