<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Attribute;
use Blog\Validator\MinLengthConstraintValidator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class MinLength extends Constraint
{
    public function __construct(
        public string $message = "La valeur '%s' doit faire au moins %d caractères. Caractères actuel : %d",
        public int $min = 10
    ) {
    }

    public function getValidator(): string
    {
        return MinLengthConstraintValidator::class;
    }
}
