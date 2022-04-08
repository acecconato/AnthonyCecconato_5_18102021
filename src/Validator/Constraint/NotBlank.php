<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class NotBlank extends Constraint
{
    public function __construct(
        protected string $message = 'Cette valeur ne peut être vide'
    ) {
    }

    public function validate(mixed $value): bool
    {
    }
}