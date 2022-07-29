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
     * @throws AssertionFailedException
     */
    public function validate(mixed $value, ConstraintInterface $constraint, ?string $propertyPath = null): bool
    {
        $checker = new Zxcvbn();
        $strength = $checker->passwordStrength($value);

        if ($strength['score'] < self::MEDIUM) {
            throw new InvalidArgumentException(
                $constraint->message ?? "Le mot de passe n'est pas sécurisé",
                0,
                $propertyPath
            );
        }

        return true;
    }
}