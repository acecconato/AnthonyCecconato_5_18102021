<?php

declare(strict_types=1);

namespace Blog\Resolver;

use Closure;

class Option
{
    public function __construct(
        private readonly string $name,
        private string $defaultValue = '',
        private Closure|null $validator = null
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    /**
     * @param  string $defaultValue
     * @return Option
     */
    public function setDefaultValue(string $defaultValue): self
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasDefaultValue(): bool
    {
        return (bool)$this->defaultValue;
    }

    /**
     * @return Closure
     */
    public function getValidator(): Closure
    {
        return $this->validator;
    }

    /**
     * @param  Closure $validator
     * @return Option
     */
    public function setValidator(Closure $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    public function isValid(mixed $value): bool
    {
        if ($this->validator instanceof \Closure) {
            return (bool)call_user_func($this->validator, $value);
        }

        return true;
    }
}

