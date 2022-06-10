<?php

declare(strict_types=1);

namespace Blog\Validator;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Blog\Validator\Constraint\ConstraintInterface;
use Blog\Validator\Constraint\MaxLength;

class MaxLengthValidator implements ConstraintValidatorInterface
{
    /**
     * @param MaxLength $constraint
     * @throws AssertionFailedException
     */
    public function validate(mixed $value, ConstraintInterface $constraint, ?string $propertyPath = null): bool
    {
        return Assertion::maxLength($value, $constraint->maxLength, $constraint->message, $propertyPath);
    }
}