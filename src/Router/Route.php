<?php

declare(strict_types=1);

namespace Blog\Router;

final class Route
{
    public function __construct(
        private string $name,
        private string $path,
        private array $parameters = [],
        private array $methods = ['GET'],
        private array $vars = [],
    ) {
    }

    public function match(string $path, string $method): bool
    {

    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getVars(): array
    {
        return $this->vars;
    }

    public function getVarsNames(): array
    {
        preg_match_all('/{[^}]*}/', $this->path, $matches);

        return reset($matches);
    }

    public function hasVars(array $vars): bool
    {

    }
}
