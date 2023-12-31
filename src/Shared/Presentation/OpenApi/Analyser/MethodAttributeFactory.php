<?php

namespace App\Shared\Presentation\OpenApi\Analyser;

use App\Shared\Presentation\OpenApi\Attributes\Payload;
use App\Shared\Presentation\OpenApi\Attributes\Query;
use OpenApi\Annotations\Operation;
use OpenApi\Attributes as OA;
use OpenApi\Context;
use OpenApi\Generator;

class MethodAttributeFactory implements AttributeFactory
{
    public function build(\Reflector $reflector, array $annotations, Context $context): array
    {
        if (!$reflector instanceof \ReflectionMethod) {
            return $annotations;
        }

        foreach ($annotations as $annotation) {
            if (!$annotation instanceof Operation) {
                continue;
            }

            if ($annotation instanceof OA\Post || $annotation instanceof OA\Put || $annotation instanceof OA\Patch) {
                if (Generator::isDefault($annotation->requestBody)) {
                    $this->guessRequestBody($reflector, $context, $annotation);
                }
            }

            if (Generator::isDefault($annotation->parameters)) {
                $this->guessParameters($reflector, $context, $annotation);
            }

            if (Generator::isDefault($annotation->responses)) {
                $this->guessResponses($reflector, $context, $annotation);
            }
        }

        return $annotations;
    }

    protected function guessRequestBody(\ReflectionMethod $reflector, Context $context, Operation $annotation): void
    {
        foreach ($reflector->getParameters() as $rp) {
            foreach ($rp->getAttributes(Payload::class, \ReflectionAttribute::IS_INSTANCEOF) as $_) {
                $type = (($rnt = $rp->getType()) && $rnt instanceof \ReflectionNamedType) ? $rnt->getName() : null;

                if (null === $type) {
                    continue;
                }

                $annotation->requestBody = new OA\RequestBody(required: !$rnt->allowsNull());
                $annotation->requestBody->_context = new Context(['nested' => $annotation], $context);
                $jsonContent = new OA\JsonContent(type: $type);
                $jsonContent->_context = new Context(['nested' => $annotation->requestBody], $context);
                $annotation->requestBody->merge([$jsonContent]);
            }
        }
    }

    protected function guessParameters(\ReflectionMethod $reflector, Context $context, Operation $annotation): void
    {
        $parameters = [];
        foreach ($reflector->getParameters() as $rp) {
            foreach ($rp->getAttributes(Query::class, \ReflectionAttribute::IS_INSTANCEOF) as $_) {
                if ((null === $rnt = $rp->getType()) || !$rnt instanceof \ReflectionNamedType || $rnt->isBuiltin() || !class_exists($rnt->getName())) {
                    continue;
                }

                $parameterReflector = new \ReflectionClass($rnt->getName());

                foreach ($parameterReflector->getProperties(\ReflectionProperty::IS_PUBLIC) as $propertyReflector) {
                    $parameter = new OA\QueryParameter(name: $propertyReflector->getName());
                    if ($propertyReflector->isDefault()) {
                        $parameter->example = $propertyReflector->getDefaultValue();
                    }
                    $parameter->_context = new Context(['nested' => $annotation], $context);
                    $parameters[] = $parameter;
                }
            }
        }

        if ($parameters) {
            $annotation->parameters = $parameters;
        }
    }

    protected function guessResponses(\ReflectionMethod $reflector, Context $context, Operation $annotation): void
    {
        $isVoid = (null === $rrt = $reflector->getReturnType()) || ($rrt->isBuiltin() && $rrt->getName() !== 'array') || !$rrt instanceof \ReflectionNamedType;
        $isPost = $annotation instanceof OA\Post;
        $isMutable = $annotation instanceof OA\Post || $annotation instanceof OA\Put || $annotation instanceof OA\Patch;

        if ($isVoid) {
            $statusCode = 204;
        } else {
            $statusCode = $isPost ? 201 : 200;
        }
        $successResponse = new OA\Response(response: $statusCode, description: 'Successful');
        $successResponse->_context = new Context(['nested' => $annotation], $context);
        if (!$isVoid) {
            $jsonContent = new OA\JsonContent(type: $rrt->getName());
            $jsonContent->_context = new Context(['nested' => $successResponse], $context);
            $successResponse->merge([$jsonContent]);
        }

        $responses = [$successResponse];

        if ($isMutable) {
            $validationErrorResponse = new OA\Response(response: 422, description: 'Validation error');
            $validationErrorResponse->_context = new Context(['nested' => $annotation], $context);
            $responses[] = $validationErrorResponse;
        }

        $annotation->responses = $responses;
    }
}
