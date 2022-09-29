<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Attribute;
use Blog\Validator\HIBPConstraintValidator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class HIBP extends Constraint
{
    public function __construct(
        public string $message = "Le mot de passe sélectionné est présent dans une brèche de données"
    ) {
    }

    public function getValidator(): string
    {
        return HIBPConstraintValidator::class;
    }
}
