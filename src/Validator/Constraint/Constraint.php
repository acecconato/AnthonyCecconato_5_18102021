<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Exception;

abstract class Constraint implements ConstraintValidatorInterface
{
    protected string $message = 'Constraint validation error';

    public function getMessage(): string
    {
        return $this->message;
    }
}