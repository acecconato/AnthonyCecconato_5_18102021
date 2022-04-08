<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class Email extends Constraint
{
    public function __construct(string $message = null)
    {
        $this->message = "%s n'est pas une adresse mail valide";

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
        return Assertion::email($value, $this->message, $propertyPath);
    }
}