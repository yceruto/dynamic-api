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
        try {
            Uuid::fromString($value);
        } catch (\InvalidArgumentException $e) {
            throw new DomainException($e->getMessage(), $e->getCode(), $e);
        }

        $this->value = $value;
    }
}
