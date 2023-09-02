<?php

namespace App\Shared\Presentation\Controller;

use App\Shared\Domain\Bus\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class CommandAction extends AbstractController
{
    protected function commandBus(): CommandBus
    {
        return $this->container->get(CommandBus::class);
    }

    public static function getSubscribedServices(): array
    {
        return parent::getSubscribedServices() + [
            CommandBus::class,
        ];
    }
}
