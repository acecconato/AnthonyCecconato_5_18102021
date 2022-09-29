<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Attribute;
use Blog\Validator\EmailConstraintValidator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class Email extends Constraint
{
    public function __construct(
        public string $message = "'%s' n'est pas une adresse mail valide"
    ) {
    }

    public function getValidator(): string
    {
        return EmailConstraintValidator::class;
    }
}
