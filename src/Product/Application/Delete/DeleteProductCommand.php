<?php

namespace App\Product\Application\Delete;

use App\Shared\Domain\Bus\Command\Command;

readonly class DeleteProductCommand implements Command
{
    public function __construct(public string $id)
    {
    }
}
