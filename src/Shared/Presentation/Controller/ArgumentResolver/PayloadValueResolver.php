<?php

namespace App\Shared\Presentation\Controller\ArgumentResolver;

use App\Shared\Presentation\Provider\GroupProvider;
use App\Shared\Presentation\Request\Attribute\Payload;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\UnsupportedFormatException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class PayloadValueResolver implements ValueResolverInterface
{
    /**
     * @see \Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT
     * @see DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS
     */
    private const CONTEXT_DENORMALIZE = [
        'disable_type_enforcement' => true,
        'collect_denormalization_errors' => true,
    ];

    /**
     * @see DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS
     */
    private const CONTEXT_DESERIALIZE = [
        'collect_denormalization_errors' => true,
    ];

    public function __construct(
        private SerializerInterface&DenormalizerInterface $serializer,
        private ValidatorInterface $validator,
        private GroupProvider $groupProvider,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var Payload $attribute */
        $attribute = $argument->getAttributesOfType(Payload::class, ArgumentMetadata::IS_INSTANCEOF)[0] ?? null;

        if (!$attribute) {
            return [];
        }

        if ($argument->isVariadic()) {
            throw new \LogicException(sprintf('Mapping variadic argument "$%s" is not supported.', $argument->getName()));
        }

        if (!$type = $argument->getType()) {
            throw new \LogicException(sprintf('Could not resolve the "$%s" controller argument: argument should be typed.', $argument->getName()));
        }

        $violations = new ConstraintViolationList();
        $payload = $this->mapRequestPayload($request, $type, $attribute);

        if (null !== $payload) {
            $groups = $this->groupProvider->groups($payload);
            $violations->addAll($this->validator->validate($payload, null, $groups));
        }

        if (\count($violations)) {
            throw new HttpException(422, implode("\n", array_map(static fn ($e) => $e->getMessage(), iterator_to_array($violations))), new ValidationFailedException($payload, $violations));
        }

        return [$payload];
    }

    private function mapRequestPayload(Request $request, string $type, Payload $attribute): ?object
    {
        if (null === $format = $request->getContentTypeFormat()) {
            throw new HttpException(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, 'Unsupported format.');
        }

        if ('' === $data = $request->getContent()) {
            return null;
        }

        $context = [];
        if ($attribute->deserializerGroups) {
            $context['groups'] = $attribute->deserializerGroups;
            $context['groups'][] = 'Default';
        }

        try {
            return $this->serializer->deserialize($data, $type, $format, self::CONTEXT_DESERIALIZE + $context);
        } catch (UnsupportedFormatException $e) {
            throw new HttpException(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, sprintf('Unsupported format: "%s".', $format), $e);
        } catch (NotEncodableValueException $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, sprintf('Request payload contains invalid "%s" data.', $format), $e);
        }
    }
}
