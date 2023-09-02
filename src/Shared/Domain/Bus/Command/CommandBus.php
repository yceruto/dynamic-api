<?php

namespace App\Shared\Domain\Bus\Command;

interface CommandBus
{
    public function execute(Command $command): mixed;
}
