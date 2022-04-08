<?php

declare(strict_types=1);

namespace Blog\Validator;

use Assert\InvalidArgumentException;
use Blog\Validator\Constraint\Constraint;
use Exception;

final class Validator implements ValidatorInterface
{
    /** @var array<array-key, array{propertyPath: string, message: string}> */
    private array $errors = [];

    /**
     * @param object $object
     * @return bool|array<string>
     * @throws Exception
     */
    public function validateObject(object $object): bool|array
    {
        $reflObj = new \ReflectionObject($object);

        foreach ($reflObj->getProperties() as $property) {
            foreach ($property->getAttributes() as $attribute) {
                $pattern = '^' . __NAMESPACE__ . '\\Constraint\\(.*)$';
                $pattern = '/' . str_replace('\\', '\/', $pattern) . '/';

                if (preg_match($pattern, str_replace('\\', '/', $attribute->getName()), $matches)) {
                    /** @var Constraint $constraint */
                    $constraint = $attribute->newInstance();
                    $this->validate($property->getValue($object), $constraint, $property->getName());
                }
            }
        }

        if (count($this->getErrors())) {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @param string $propertyPath
     * @return bool
     */
    public function validate(mixed $value, Constraint $constraint, string $propertyPath = ''): bool
    {
        try {
            return $constraint->validate($value, $propertyPath);
        } catch (InvalidArgumentException $e) {
            $this->addError($e->getMessage(), $e->getPropertyPath());
            return false;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $message, string $propertyPath = ''): self
    {
        $this->errors[] = ['propertyPath' => $propertyPath, 'message' => $message];
        return $this;
    }
}