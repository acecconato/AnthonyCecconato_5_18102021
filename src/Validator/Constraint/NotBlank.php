<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Attribute;
use Blog\Validator\NotBlankConstraintValidator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class NotBlank extends Constraint
{
    public function __construct(
        public string $message = "Cette valeur ne doit pas être vide"
    ) {
    }

    public function getValidator(): string
    {
        return NotBlankConstraintValidator::class;
    }
}