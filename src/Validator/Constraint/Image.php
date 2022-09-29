<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Attribute;
use Blog\Validator\ImageConstraintValidator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class Image extends Constraint
{
    public function __construct(
        public string $message = "%s doit être une image valide"
    ) {
    }

    public function getValidator(): string
    {
        return ImageConstraintValidator::class;
    }
}
