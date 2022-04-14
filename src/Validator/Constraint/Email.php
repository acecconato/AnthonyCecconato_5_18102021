<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class NotBlank extends Constraint
{
    public function __construct(
        protected string $message = 'Cette valeur ne peut être vide'
    ) {
    }

    /**
     * @param mixed $value
     * @param string $propertyPath
     * @return bool
     * @throws AssertionFailedException
     */
    public function validate(mixed $value, string $propertyPath): bool
    {
        return Assertion::notBlank($value, $this->message, $propertyPath);
    }
}