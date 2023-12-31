<?php

namespace App\Shared\Presentation\OpenApi\Attributes;

use App\Shared\Presentation\Controller\ArgumentResolver\PayloadValueResolver;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class Payload extends ValueResolver
{
    public function __construct(
        string $resolver = PayloadValueResolver::class,
    ) {
        parent::__construct($resolver);
    }
}
