<?php

declare(strict_types=1);

namespace Blog\Validator;

use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use Blog\Validator\Constraint\ConstraintInterface;
use Blog\Validator\Constraint\HIBP;

class HIBPConstraintValidator implements ConstraintValidatorInterface
{
    /**
     * @param HIBP $constraint
     * @throws AssertionFailedException
     */
    public function validate(mixed $value, ConstraintInterface $constraint, ?string $propertyPath = null): bool
    {
        if (password_is_exposed($value)) {
            throw new InvalidArgumentException(
                $constraint->message ?? "Le mot de passe est présent dans une brèche de données",
                0,
                $propertyPath
            );
        }

        return true;
    }
}