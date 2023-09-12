<?php

namespace App\Shared\Presentation\Response;

use App\Shared\Presentation\Provider\GroupProvider;
use OpenApi\Annotations\Operation;
use OpenApi\Attributes as OA;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

readonly class ControllerResultSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private GroupProvider $provider,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $result = $event->getControllerResult();

        if (!$result) {
            $event->setResponse(new Response(status: 204));
            return;
        }

        if ($result instanceof Response) {
            return;
        }

        $groups = $this->provider->groups($result);
        $content = $this->serializer->serialize($result, 'json', ['groups' => $groups]);
        $statusCode = $this->guessStatusCode($event->controllerArgumentsEvent->getAttributes());
        $event->setResponse(new JsonResponse($content, $statusCode, json: true));
    }

    protected function guessStatusCode(array $controllerAttributes): int
    {
        foreach ($controllerAttributes as $attributes) {
            foreach ($attributes as $attribute) {
                if (!$attribute instanceof Operation) {
                    continue;
                }

                if (is_array($attribute->responses)) {
                    foreach ($attribute->responses as $res) {
                        if (is_numeric($res->response) && $res->response >= 200 && $res->response < 300) {
                            return (int) $res->response;
                        }
                    }
                }

                if ($attribute instanceof OA\Post) {
                    return 201;
                }
            }
        }

        return 200;
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::VIEW => 'onKernelView'];
    }
}
