<?php

namespace App\Shared\Presentation\Request;

use App\Shared\Presentation\OpenApi\Processor\Path\PathPublisher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class RequestPathSubscriber implements EventSubscriberInterface
{
    public function __construct(private PathPublisher $publisher)
    {
    }

    public function __invoke(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $operationId = $request->attributes->getString('_route');

        if ($this->publisher->publish($operationId, ['request' => $request])) {
            return;
        }

        $event->setResponse(new JsonResponse(['message' => 'Endpoint disabled'], 400));
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => ['__invoke', 30]];
    }
}
