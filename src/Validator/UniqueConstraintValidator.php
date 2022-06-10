<?php

declare(strict_types=1);

namespace Blog\Validator;

use Assert\InvalidArgumentException;
use Blog\ORM\EntityManager;
use Blog\Validator\Constraint\ConstraintInterface;
use Blog\Validator\Constraint\Unique;

class UniqueConstraintValidator implements ConstraintValidatorInterface
{
    const NOT_UNIQUE = 992;

    public function __construct(
        private EntityManager $entityManager
    ) {
    }

    /**
     * @param Unique $constraint
     */
    public function validate(mixed $value, ConstraintInterface $constraint, ?string $propertyPath = null): bool
    {
        if (!$this->entityManager->count($constraint->entityFqcn, $constraint->column, $value)) {
            throw new InvalidArgumentException(
                sprintf($constraint->message ?: '%s is not unique', $value),
                self::NOT_UNIQUE,
                $propertyPath
            );
        };
    }
}