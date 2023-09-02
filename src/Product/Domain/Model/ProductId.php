<?php

namespace App\Product\Domain\Model;

use DomainException;
use Symfony\Component\Uid\Uuid;

readonly class ProductId
{
    private string $value;

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function value(): string
    {
        return $this->value;
    }

    private function __construct(string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new DomainException(sprintf('Invalid UUID value %s', $value));
        }

        $this->value = $value;
    }
}
