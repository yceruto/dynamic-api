<?php

namespace App\Product\Domain\Error;

use App\Product\Domain\Model\ProductId;
use App\Shared\Domain\Error\EntityNotFoundError;

class ProductNotFoundError extends EntityNotFoundError
{
    public static function create(ProductId $id): self
    {
        return new self(sprintf('Product with id <%s> not found', $id->value()));
    }
}
