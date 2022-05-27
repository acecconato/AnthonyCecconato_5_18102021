<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class MinLength extends Constraint
{
    public function __construct(string $message = null, protected int $maxLen = 255)
    {
        $this->message = "la valeur '%s' doit faire au moins %d caractères. Caractères actuel : %d";

        if ($message) {
            $this->message = $message;
        }
    }

    /**
     * @param mixed $value
     * @param string $propertyPath
     * @return bool
     * @throws AssertionFailedException
     */
    public function validate(mixed $value, string $propertyPath): bool
    {
        return Assertion::minLength($value, $this->maxLen, $this->message, $propertyPath);
    }
}