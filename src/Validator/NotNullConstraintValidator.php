<?php

declare(strict_types=1);

namespace Blog\Validator;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Blog\Validator\Constraint\ConstraintInterface;
use Blog\Validator\Constraint\NotNull;

class NotNullConstraintValidator implements ConstraintValidatorInterface
{
    /**
     * @param  NotNull $constraint
     * @throws AssertionFailedException
     */
    public function validate(mixed $value, ConstraintInterface $constraint, ?string $propertyPath = null): bool
    {
        $message = sprintf($constraint->message, $propertyPath);

        // If the propertyPath is defined, replace %s from the message by the property name instead of the <NULL> value
        if (!$propertyPath) {
            $message = $constraint->message;
        }

        return Assertion::notNull(
            $value,
            $message,
            $propertyPath
        );
    }
}
