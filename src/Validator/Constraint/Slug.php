<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Assert\InvalidArgumentException;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
final class Slug extends Constraint
{
    const INVALID_SLUG = 991;

    public function __construct(string $message = null, protected int $maxLen = 255)
    {
        $this->message = "la valeur '%s' n'est pas un slug valide";

        if ($message) {
            $this->message = $message;
        }
    }

    /**
     * @param mixed $value
     * @param string $propertyPath
     * @return bool
     */
    public function validate(mixed $value, string $propertyPath): bool
    {
        return $this->assert($value, $this->message, $propertyPath);
    }

    private function assert(string $value, string $message, string $propertyPath): bool
    {
        if (!preg_match('/^[a-z][-a-z\d]*$/', $value)) {
            throw new InvalidArgumentException(
                sprintf($message ?: '%s is not a valid slug', $value),
                self::INVALID_SLUG,
                $propertyPath
            );
        }

        return true;
    }
}