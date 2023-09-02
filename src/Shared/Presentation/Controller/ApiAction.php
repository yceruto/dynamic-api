<?php

namespace App\Shared\Presentation\Controller;

use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Query\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class ApiAction extends AbstractController
{
    protected function commandBus(): CommandBus
    {
        return $this->container->get(CommandBus::class);
    }

    protected function queryBus(): QueryBus
    {
        return $this->container->get(QueryBus::class);
    }

    public static function getSubscribedServices(): array
    {
        return parent::getSubscribedServices() + [
            CommandBus::class,
            QueryBus::class,
        ];
    }
}
