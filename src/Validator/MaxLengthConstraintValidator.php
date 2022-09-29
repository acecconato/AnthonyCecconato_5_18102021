<?php

declare(strict_types=1);

namespace Blog\Validator;

use Assert\Assertion;
use Blog\Validator\Constraint\ConstraintInterface;
use Blog\Validator\Constraint\MaxLength;

class MaxLengthConstraintValidator implements ConstraintValidatorInterface
{
    /**
     * @param MaxLength $constraint
     */
    public function validate(mixed $value, ConstraintInterface $constraint, ?string $propertyPath = null): bool
    {
        return Assertion::nullOrMaxLength($value, $constraint->max, $constraint->message, $propertyPath);
    }
}
