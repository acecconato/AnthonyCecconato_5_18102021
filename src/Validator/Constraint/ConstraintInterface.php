<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

interface ConstraintInterface
{
    public function getValidator(): string;

    public static function getInstance(): ConstraintInterface;
}
