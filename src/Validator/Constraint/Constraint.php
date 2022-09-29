<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

abstract class Constraint implements ConstraintInterface
{
    public string $message = 'Constraint validation error';

    public static function getInstance(): ConstraintInterface
    {
        $constraint = static::class;
        return new $constraint();
    }
}
