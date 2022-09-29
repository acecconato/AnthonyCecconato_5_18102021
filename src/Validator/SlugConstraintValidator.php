<?php

declare(strict_types=1);

namespace Blog\Validator;

use Assert\InvalidArgumentException;
use Blog\Validator\Constraint\ConstraintInterface;
use Blog\Validator\Constraint\Slug;

class SlugConstraintValidator implements ConstraintValidatorInterface
{
    public const INVALID_SLUG = 991;

    /**
     * @param Slug $constraint
     */
    public function validate(mixed $value, ConstraintInterface $constraint, ?string $propertyPath = null): bool
    {
        if (!preg_match('/^[a-z][-a-z\d]*$/', $value)) {
            throw new InvalidArgumentException(
                sprintf($constraint->message ?: '%s is not a valid slug', $value),
                self::INVALID_SLUG,
                $propertyPath
            );
        }

        return true;
    }
}
