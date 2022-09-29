<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Attribute;
use Blog\Validator\UsernameConstraintValidator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class Username extends Constraint
{
    public function __construct(
        public string $message = "Le nom d'utilisateur ne peut contenir que des lettres et des chiffres"
    ) {
    }

    public function getValidator(): string
    {
        return UsernameConstraintValidator::class;
    }
}
