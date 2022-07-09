<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Attribute;
use Blog\Validator\NotNullConstraintValidator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class NotNull extends Constraint
{
    public function __construct(
        public string $message = "La valeur de la propriété '%s' ne doit pas être nulle"
    ) {
    }

    public function getValidator(): string
    {
        return NotNullConstraintValidator::class;
    }
}