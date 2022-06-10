<?php

declare(strict_types=1);

namespace Blog\Validator;

use Assert\InvalidArgumentException;
use Blog\DependencyInjection\ContainerInterface;
use Blog\Validator\Constraint\Constraint;
use Blog\Validator\Constraint\ConstraintInterface;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

final class Validator implements ValidatorInterface
{
    public function __construct(
        private ContainerInterface $container
    ) {
    }

    /** @var array<array-key, array{propertyPath: string, message: string}> */
    private array $errors = [];

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function validateObject(object $object): bool
    {
        $reflObj = new \ReflectionObject($object);

        foreach ($reflObj->getProperties() as $property) {
            foreach ($property->getAttributes() as $attribute) {
                $pattern = '^' . __NAMESPACE__ . '\\Constraint\\(.*)$';
                $pattern = '/' . str_replace('\\', '\/', $pattern) . '/';

                if (preg_match($pattern, str_replace('\\', '/', $attribute->getName()))) {
                    /** @var Constraint $constraint */
                    $constraint = $attribute->newInstance();
                    $this->validate($property->getValue($object), $constraint, $property->getName());
                }
            }
        }

        if (count($this->errors)) {
            return false;
        }

        return true;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception|ContainerExceptionInterface
     */
    public function validate(mixed $value, ConstraintInterface $constraint, string $propertyPath = ''): bool
    {
        /** @var ConstraintValidatorInterface $validator */
        $validator = $this->container->get($constraint->getValidator());

        if (!$validator) {
            throw new Exception("Validator for " . $constraint::class . " cannot be loaded");
        }

        try {
            return $validator->validate($value, $constraint, $propertyPath);
        } catch (InvalidArgumentException $e) {
            $this->addError($e->getMessage(), $e->getPropertyPath() ?? $propertyPath);
            return false;
        }
    }

    public function getErrors(): array
    {
        $tempErrors = $this->errors;
        $this->clear();
        return $tempErrors;
    }

    public function clear(): void
    {
        $this->errors = [];
    }

    public function addError(string $message, string $propertyPath = ''): Validator
    {
        $this->errors[] = ['propertyPath' => $propertyPath, 'message' => $message];
        return $this;
    }
}