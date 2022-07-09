<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Assert\InvalidArgumentException;
use Attribute;
use Blog\Validator\SlugConstraintValidator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class Slug extends Constraint
{
    public function __construct(
        public string $message = "La valeur '%s' n'est pas un slug valide"
    ) {
    }

    public function getValidator(): string
    {
        return SlugConstraintValidator::class;
    }
}