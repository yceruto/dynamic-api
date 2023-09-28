<?php

namespace App\Shared\Domain\Error;

use DomainException;

class FeatureUnavailableError extends DomainException
{
    public static function create(string $message = 'Feature is unavailable'): self
    {
        return new self($message);
    }
}
