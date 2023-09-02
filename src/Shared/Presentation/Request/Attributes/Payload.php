<?php

namespace App\Shared\Presentation\Request\Attributes;

use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class Payload extends MapRequestPayload
{
}
