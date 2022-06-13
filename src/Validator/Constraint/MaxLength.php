<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Attribute;
use Blog\Validator\MaxLengthConstraintValidator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class MaxLength extends Constraint
{
    public function __construct(
        public string $message = "La valeur '%s' ne peut excéder %d caractères. Caractères actuel : %d",
        public int $maxLength = 255
    ) {
    }

    public function getValidator(): string
    {
        return MaxLengthConstraintValidator::class;
    }
}