<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

interface ConstraintValidatorInterface
{
    public function validate(mixed $value, string $propertyPath): bool;
}