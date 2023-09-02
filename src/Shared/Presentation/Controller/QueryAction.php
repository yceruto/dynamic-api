<?php

namespace App\Shared\Presentation\Controller;

use App\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class QueryAction extends AbstractController
{
    protected function queryBus(): QueryBus
    {
        return $this->container->get(QueryBus::class);
    }

    public static function getSubscribedServices(): array
    {
        return parent::getSubscribedServices() + [
            QueryBus::class,
        ];
    }
}
