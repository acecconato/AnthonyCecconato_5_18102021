<?php

declare(strict_types=1);

namespace Blog\Validator\Constraint;

use Exception;

abstract class Constraint
{
    protected \Closure|null $validator = null;

    protected mixed $value = null;

    protected string $message = 'Constraint validation error';

    protected function setValidator(\Closure $closure): self
    {
        $this->validator = $closure;
        return $this;
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws Exception
     */
    public function validate(mixed $value): bool
    {
        throw new Exception('Method validate not implemented in the original class');
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}