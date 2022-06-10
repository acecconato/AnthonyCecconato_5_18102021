<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Attribute;
use Blog\Validator\UniqueConstraintValidator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class Unique extends Constraint
{
    public function __construct(
        // TODO Q/A Voir pour faire passer entityFqcn et Column en auto lors d'un validateObject
        public string $entityFqcn,
        public string $column,
        public string $message = "La valeur '%s' existe déjà dans la base de données"
    ) {
    }

    public function getValidator(): string
    {
        return UniqueConstraintValidator::class;
    }
}