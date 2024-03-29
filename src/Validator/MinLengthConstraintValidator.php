<?php

declare(strict_types=1);

namespace Blog\Validator;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Blog\Validator\Constraint\ConstraintInterface;
use Blog\Validator\Constraint\MinLength;

class MinLengthConstraintValidator implements ConstraintValidatorInterface
{
    /**
     * @param  MinLength $constraint
     */
    public function validate(mixed $value, ConstraintInterface $constraint, ?string $propertyPath = null): bool
    {
        return Assertion::nullOrMinLength($value, $constraint->min, $constraint->message, $propertyPath);
    }
}
