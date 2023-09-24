<?php

namespace App\Shared\Presentation\Controller\ArgumentResolver;

use App\Shared\Presentation\Provider\GroupsResolver;
use App\Shared\Presentation\Request\Attribute\Payload;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Exception\UnsupportedFormatException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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
        private TranslatorInterface $translator,
        private ValidatorInterface $validator,
        private GroupsResolver $groupsResolver,
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
        try {
            $payload = $this->mapRequestPayload($request, $type);
        } catch (PartialDenormalizationException $e) {
            foreach ($e->getErrors() as $error) {
                $parameters = ['{{ type }}' => implode('|', $error->getExpectedTypes())];
                if ($error->canUseMessageForUser()) {
                    $parameters['hint'] = $error->getMessage();
                }
                $template = 'This value should be of type {{ type }}.';
                $message = $this->translator->trans($template, $parameters, 'validators');
                $violations->add(new ConstraintViolation($message, $template, $parameters, null, $error->getPath(), null));
            }
            $payload = $e->getData();
        }

        if (null === $payload) {
            return [null];
        }

        $groups = $this->groupsResolver->resolve($payload);
        $violations->addAll($this->validator->validate($payload, null, $groups));

        if (\count($violations)) {
            throw new HttpException(422, implode("\n", array_map(static fn ($e) => $e->getMessage(), iterator_to_array($violations))), new ValidationFailedException($payload, $violations));
        }

        return [$payload];
    }

    private function mapRequestPayload(Request $request, string $type): ?object
    {
        if (null === $format = $request->getContentTypeFormat()) {
            throw new HttpException(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, 'Unsupported format.');
        }

        if ('' === $data = $request->getContent()) {
            return null;
        }

        try {
            return $this->serializer->deserialize($data, $type, $format, self::CONTEXT_DESERIALIZE);
        } catch (UnsupportedFormatException $e) {
            throw new HttpException(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, sprintf('Unsupported format: "%s".', $format), $e);
        } catch (NotEncodableValueException $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, sprintf('Request payload contains invalid "%s" data.', $format), $e);
        }
    }
}
