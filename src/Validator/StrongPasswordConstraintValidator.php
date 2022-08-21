<?php

declare(strict_types=1);

namespace Blog\Validator;

use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use Blog\Validator\Constraint\ConstraintInterface;
use Blog\Validator\Constraint\StrongPassword;
use ZxcvbnPhp\Zxcvbn;

class StrongPasswordConstraintValidator implements ConstraintValidatorInterface
{
    private const VERY_WEAK = 0;
    private const WEAK = 1;
    private const MEDIUM = 2;
    private const STRONG = 3;
    private const VERY_STRONG = 4;

    /**
     * @param StrongPassword $constraint
     *
     * @throws AssertionFailedException
     */
    public function validate(mixed $value, ConstraintInterface $constraint, ?string $propertyPath = null): bool
    {
        $error = false;

        if (strlen($value) < 8) {
            $error = 'Le mot de passe doit au moins contenir 8 caractères';
        }

        if (! preg_match('/[^\da-zA-Z]/', $value)) {
            $error = 'Le mot de passe doit au moins contenir un caractère spécial';
        }

        if (! preg_match('/(?=.*[a-z])/', $value)) {
            $error = 'Le mot de passe doit au moins contenir une lettre minuscule';
        }

        if (! preg_match('/(?=.*[A-Z])/', $value)) {
            $error = 'Le mot de passe doit au moins contenir une lettre majuscule';
        }

        if (! preg_match('/(?=.*\d)/', $value)) {
            $error = 'Le mot de passe doit au moins contenir un chiffre';
        }

        if ($error) {
            throw new InvalidArgumentException(
                $error,
                0,
                $propertyPath
            );
        }

        return true;
    }
}
