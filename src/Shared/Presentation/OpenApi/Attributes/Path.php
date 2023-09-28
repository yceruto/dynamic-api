<?php

namespace App\Shared\Presentation\OpenApi\Attributes;

use OpenApi\Attributes\PathParameter;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER | \Attribute::IS_REPEATABLE)]
class Path extends PathParameter
{
}