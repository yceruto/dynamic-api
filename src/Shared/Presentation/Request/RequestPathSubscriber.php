<?php

namespace App\Shared\Presentation\Request;

use App\Shared\Domain\Error\EndpointDisabledError;
use App\Shared\Presentation\OpenApi\Processor\Publisher\EndpointPublisherContainer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class RequestPathSubscriber implements EventSubscriberInterface
{
    public function __construct(private EndpointPublisherContainer $publishers)
    {
    }

    public function __invoke(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if ('' === $publisherId = $request->attributes->getString('_publisher')) {
            return;
        }

        $endpointPublisher = $this->publishers->get($publisherId);

        try {
            if (!$endpointPublisher->publish([
                'path_id' => $request->attributes->getString('_route'),
                'request' => $request,
            ])) {
                $event->setResponse(new JsonResponse(['message' => 'Endpoint is disabled'], 400));
            }
        } catch (EndpointDisabledError $e) {
            $event->setResponse(new JsonResponse(['message' => $e->getMessage()], 400));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => ['__invoke', 30]];
    }
}
