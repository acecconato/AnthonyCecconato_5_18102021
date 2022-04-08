<?php

declare(strict_types=1);

namespace Blog\Validator;

use Blog\Validator\Constraint\Constraint;
use Exception;

final class Validator implements ValidatorInterface
{
    /** @var array<string> */
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
                    dump($this->validate($property->getValue($object), $constraint));
                }
            }
        }

        die();
    }

    /**
     * @param string $value
     * @param Constraint $constraint
     * @return bool|string
     * @throws Exception
     */
    public function validate(string $value, Constraint $constraint): bool|string
    {
        return $constraint->validate($value);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(string $message): self
    {
        $this->errors[] = $message;
        return $this;
    }
}