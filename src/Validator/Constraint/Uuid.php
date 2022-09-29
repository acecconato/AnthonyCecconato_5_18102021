<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Attribute;
use Blog\Validator\UuidConstraintValidator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class Uuid extends Constraint
{
    public function __construct(
        public string $message = "'%s' n'est pas un uuid valide"
    ) {
    }

    public function getValidator(): string
    {
        return UuidConstraintValidator::class;
    }
}
