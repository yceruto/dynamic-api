<?php

namespace App;

use App\Shared\Presentation\OpenApi\Generator;
use App\Shared\Presentation\OpenApi\Serializer\Mapping\Loader\OpenApiSerializerAttributeLoader;
use App\Shared\Presentation\OpenApi\Validator\Mapping\Loader\OpenApiValidatorAttributeLoader;
use OpenApi\Attributes as OA;
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

#[OA\Info(version: '1.0', title: 'Dynamic API')]
class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    #[Route('/')]
    public function index(): Response
    {
        return new Response(file_get_contents(dirname(__DIR__).'/templates/swagger/index.html'));
    }

    #[Route('/swagger.json')]
    public function swagger(Request $request, Generator $generator): JsonResponse
    {
        $openApi = $generator->generate([__DIR__]) ?? throw new NotFoundHttpException();
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
