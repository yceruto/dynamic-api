<?php

namespace App\Shared\Presentation\Validator\Mapping\Loader;

use App\Shared\Presentation\OpenApi\Attributes\Property;
use OpenApi\Generator;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Loader\LoaderInterface;

class OpenApiAttributeLoader implements LoaderInterface
{
    public function loadClassMetadata(ClassMetadata $metadata): bool
    {
        $reflClass = $metadata->getReflectionClass();
        $success = false;

        foreach ($reflClass->getProperties() as $property) {
            foreach ($this->getAttributes($property) as $attribute) {
                $groups = $attribute->groups;

                if ('uuid' === $attribute->format) {
                    $metadata->addPropertyConstraint($property->name, new Assert\Uuid(groups: $groups));
                    $success = true;
                }

                if (!Generator::isDefault($attribute->minLength) || !Generator::isDefault($attribute->maxLength)) {
                    $metadata->addPropertyConstraint($property->name, new Assert\Length(
                        min: Generator::isDefault($attribute->minLength) ? null : $attribute->minLength,
                        max: Generator::isDefault($attribute->maxLength) ? null : $attribute->maxLength,
                        groups: $groups,
                    ));
                    $success = true;
                }

                if (!Generator::isDefault($attribute->minItems) || !Generator::isDefault($attribute->maxItems)) {
                    $metadata->addPropertyConstraint($property->name, new Assert\Count(
                        min: Generator::isDefault($attribute->minItems) ? null : $attribute->minItems,
                        max: Generator::isDefault($attribute->maxItems) ? null : $attribute->maxItems,
                        groups: $groups,
                    ));
                    $success = true;
                }

                if (!Generator::isDefault($attribute->minimum)) {
                    $constraint = Generator::isDefault($attribute->exclusiveMinimum)
                        ? new Assert\GreaterThanOrEqual(value: $attribute->minimum, groups: $groups)
                        : new Assert\GreaterThan(value: $attribute->minimum, groups: $groups);
                    $metadata->addPropertyConstraint($property->name, $constraint);
                    $success = true;
                }

                if (!Generator::isDefault($attribute->maximum)) {
                    $constraint = Generator::isDefault($attribute->exclusiveMaximum)
                        ? new Assert\LessThanOrEqual(value: $attribute->maximum, groups: $groups)
                        : new Assert\LessThan(value: $attribute->maximum, groups: $groups);
                    $metadata->addPropertyConstraint($property->name, $constraint);
                    $success = true;
                }

                if (!Generator::isDefault($attribute->pattern)) {
                    $metadata->addPropertyConstraint($property->name, new Assert\Regex(pattern: $attribute->pattern, groups: $groups));
                    $success = true;
                }

                if (!Generator::isDefault($attribute->uniqueItems)) {
                    $metadata->addPropertyConstraint($property->name, new Assert\Unique(groups: $groups));
                    $success = true;
                }

                if (!Generator::isDefault($attribute->enum)) {
                    $metadata->addPropertyConstraint($property->name, new Assert\Type(type: $attribute->enum, groups: $groups));
                    $success = true;
                }

                if (!Generator::isDefault($attribute->multipleOf)) {
                    $metadata->addPropertyConstraint($property->name, new Assert\DivisibleBy(value: $attribute->multipleOf, groups: $groups));
                    $success = true;
                }

                if (!Generator::isDefault($attribute->const)) {
                    $metadata->addPropertyConstraint($property->name, new Assert\EqualTo(value: $attribute->const, groups: $groups));
                    $success = true;
                }

                if (!$type = $property->getType()) {
                    continue;
                }

                if (!$type->allowsNull()) {
                    $metadata->addPropertyConstraint($property->name, new Assert\NotNull(groups: $groups));
                    $success = true;
                }

                if (!$type->isBuiltin()) {
                    $metadata->addPropertyConstraint($property->name, new Assert\Valid(groups: $groups));
                    $success = true;
                }
            }
        }

        return $success;
    }

    /**
     * @return iterable<Property>
     */
    protected function getAttributes(\ReflectionProperty $reflection): iterable
    {
        foreach ($reflection->getAttributes(Property::class, \ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            yield $attribute->newInstance();
        }
    }
}
