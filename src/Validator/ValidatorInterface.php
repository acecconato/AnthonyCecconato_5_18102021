<?php

namespace Blog\Validator;

use Blog\Validator\Constraint\ConstraintInterface;

interface ValidatorInterface
{
    public function validateObject(object $object): bool;

    public function validate(mixed $value, ConstraintInterface $constraint, string $propertyPath = ''): bool;

    public function getErrors(): array;

    public function clear(): void;

    public function addError(string $message, string $propertyPath = ''): ValidatorInterface;
}