<?php

namespace Blog\Validator;

use Blog\Validator\Constraint\Constraint;

interface ValidatorInterface
{
    /**
     * @param object $object
     * @return bool|array<string>
     */
    public function validateObject(object $object): bool|array;

    public function validate(string $value, Constraint $constraint): bool|string;

    /**
     * @return array<string>
     */
    public function getErrors(): array;

    public function addError(string $message): self;
}