<?php

namespace App\Shared\Presentation\Request;

use App\Shared\Domain\Error\FeatureDisabledError;
use App\Shared\Presentation\Publisher\FeaturePublisherContainer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Determines whether a request can be processed or not.
 */
readonly class RequestFeatureSubscriber implements EventSubscriberInterface
{
    public function __construct(private FeaturePublisherContainer $publishers)
    {
    }

    public function __invoke(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$publisherId = $request->attributes->getString('_publisher')) {
            return;
        }

        $featurePublisher = $this->publishers->get($publisherId);

        try {
            if (!$featurePublisher->publish([
                'path_id' => $request->attributes->getString('_route'),
                'subject' => $request,
            ])) {
                $event->setResponse(new JsonResponse(['message' => 'Endpoint is disabled'], 400));
            }
        } catch (FeatureDisabledError $e) {
            $event->setResponse(new JsonResponse(['message' => $e->getMessage()], 400));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => ['__invoke', 30]];
    }
}
