<?php

namespace App;

use App\Shared\Presentation\OpenApi\Analyser\AttributeAnnotationFactory;
use App\Shared\Presentation\OpenApi\Processor\Path\PathPublisher;
use App\Shared\Presentation\OpenApi\Processor\PathPublisherProcessor;
use OpenApi\Analysers\DocBlockAnnotationFactory;
use OpenApi\Analysers\ReflectionAnalyser;
use OpenApi\Attributes as OA;
use OpenApi\Generator;
use OpenApi\Processors;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Annotation\Route;

#[OA\Info(version: '1.0', title: 'Catalog API')]
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    #[Route('/')]
    public function index(): Response
    {
        return new Response(file_get_contents(dirname(__DIR__).'/templates/swagger/index.html'));
    }

    #[Route('/swagger.json')]
    public function swagger(PathPublisherProcessor $pathDeciderProcessor): JsonResponse
    {
        $openApi = Generator::scan([__DIR__], [
            'analyser' => new ReflectionAnalyser([
                new DocBlockAnnotationFactory(),
                new AttributeAnnotationFactory(),
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
                $pathDeciderProcessor,
            ],
        ]) ?? throw new NotFoundHttpException();

        $openApi->servers = [
            new OA\Server(url: 'https://127.0.0.1:8000'),
        ];

        return new JsonResponse($openApi->toJson(), json: true);
    }
}
