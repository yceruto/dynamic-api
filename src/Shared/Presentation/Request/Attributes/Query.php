<?php

namespace App\Shared\Presentation\Request\Attributes;

use Symfony\Component\HttpKernel\Attribute\MapQueryString;

#[\Attribute(\Attribute::TARGET_PARAMETER)]
class Query extends MapQueryString
{
}
