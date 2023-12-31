<?php

namespace App\Shared\Presentation\OpenApi\Analyser;

use IntBackedEnum;
use OpenApi\Attributes\Property;
use OpenApi\Context;
use OpenApi\Generator;
use UnitEnum;

class PropertyAttributeFactory implements AttributeFactory
{
    public function build(\Reflector $reflector, array $annotations, Context $context): array
    {
        if (!$reflector instanceof \ReflectionProperty || !$type = $reflector->getType()) {
            return $annotations;
        }

        foreach ($annotations as $annotation) {
            if (!$annotation instanceof Property) {
                continue;
            }

            if (Generator::isDefault($annotation->default) && $reflector->hasDefaultValue() && null !== $default = $reflector->getDefaultValue()) {
                $annotation->default = $default;
            }

            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin() && is_subclass_of($type->getName(), UnitEnum::class)) {
                $annotation->enum = $type->getName()::cases();
                $annotation->type = is_subclass_of($type->getName(), IntBackedEnum::class) ? 'integer' : 'string';
            }
        }

        return $annotations;
    }
}
