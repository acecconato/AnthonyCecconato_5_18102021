<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Attribute;
use Blog\Validator\StrongPasswordConstraintValidator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class StrongPassword extends Constraint
{
    public function __construct(
        public string $message = "Le mot de passe n'est pas suffisamment sécurisé. Vous devriez y rajouter des caractères spéciaux, des lettres, des symboles, etc..."
    ) {
    }

    public function getValidator(): string
    {
        return StrongPasswordConstraintValidator::class;
    }
}
