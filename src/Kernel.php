<?php

namespace App;

use App\Product\Domain\Model\ProductId;
use App\Shared\Presentation\OpenApi\Analyser\AttributeAnnotationFactory;
use OpenApi\Analysers\DocBlockAnnotationFactory;
use OpenApi\Analysers\ReflectionAnalyser;
use OpenApi\Attributes as OA;
use OpenApi\Generator;
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
    public function swagger(): JsonResponse
    {
        $openApi = Generator::scan([__DIR__], [
            'analyser' => new ReflectionAnalyser([
                new DocBlockAnnotationFactory(),
                new AttributeAnnotationFactory(),
            ]),
        ]) ?? throw new NotFoundHttpException();

        $openApi->servers = [
            new OA\Server(url: 'https://127.0.0.1:8000'),
        ];

        return new JsonResponse($openApi->toJson(), json: true);
    }
}
