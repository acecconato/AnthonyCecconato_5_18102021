<?php

declare(strict_types=1);

namespace Blog\Validator;

use Blog\Validator\Constraint\ConstraintInterface;

interface ConstraintValidatorInterface
{
    public function validate(mixed $value, ConstraintInterface $constraint, ?string $propertyPath = null): bool;
}
