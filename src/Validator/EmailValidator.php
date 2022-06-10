<?php

declare(strict_types=1);

namespace Blog\Validator;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Blog\Validator\Constraint\ConstraintInterface;
use Blog\Validator\Constraint\Email;

class EmailValidator implements ConstraintValidatorInterface
{
    /**
     * @param Email $constraint
     * @throws AssertionFailedException
     */
    public function validate(mixed $value, ConstraintInterface $constraint, ?string $propertyPath = null): bool
    {
        return Assertion::email($value, $constraint->message, $propertyPath);
    }
}