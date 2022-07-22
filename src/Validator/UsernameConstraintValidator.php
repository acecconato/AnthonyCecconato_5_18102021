<?php

declare(strict_types=1);

namespace Blog\Validator;

use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use Blog\Validator\Constraint\ConstraintInterface;
use Blog\Validator\Constraint\HIBP;

class UsernameConstraintValidator implements ConstraintValidatorInterface
{
    /**
     * @param HIBP $constraint
     * @throws AssertionFailedException
     */
    public function validate(mixed $value, ConstraintInterface $constraint, ?string $propertyPath = null): bool
    {
        if (!preg_match('/^[A-Za-z0-9]*$/', $value)) {
            throw new InvalidArgumentException(
                $constraint->message ?? "Le nom d'utilisateur est incorrect",
                0,
                $propertyPath
            );
        }

        return true;
    }
}
