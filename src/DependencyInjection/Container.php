<?php

declare(strict_types=1);

namespace Blog\DependencyInjection;

use Blog\DependencyInjection\Exceptions\NotFoundException;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

final class Container implements ContainerInterface
{
    /**
     * @var array<string>
     */
    private array $parameters = [];

    /**
     * @var Definition[]
     */
    private array $definitions = [];

    /**
     * @var array<string, object>
     */
    private array $instances = [];

    /**
     * @var array<string>
     */
    private array $aliases = [];

    /**
     * @inheritDoc
     * @throws     ReflectionException
     */
    public function register(string $id): self
    {
        $reflectionClass = new ReflectionClass($id);

        if ($reflectionClass->isInterface()) {
            $this->register($this->aliases[$id]);
            // TODO Q/A: Pourquoi passer la variable ci-dessous en référence ?
            $this->definitions[$id] = &$this->definitions[$this->aliases[$id]];
            return $this;
        }

        $dependencies = [];

        if (null !== $reflectionClass->getConstructor()) {
            $dependencies = array_map(
            // TODO Q/A Erreur PHPSTAN "Call to an undefined method ReflectionType::getName()." ??
            /* @phpstan-ignore-next-line */
                fn(ReflectionParameter $parameter) => $this->getDefinition($parameter->getType()->getName()),
                array_filter(
                    $reflectionClass->getConstructor()->getParameters(),
                    // TODO Q/A Par quoi peut-on remplacer les getClass() depréciés ?
                    fn(ReflectionParameter $parameter) => $parameter->getClass()
                )
            );
        }

        $aliases = array_filter($this->aliases, fn(string $alias) => $id === $alias);

        $this->definitions[$id] = new Definition(id: $id, aliases: $aliases, dependencies: $dependencies);

        return $this;
    }

    /**
     * Get a definition
     * If the excepted definition does not exist then we register it before returning it.
     *
     * @param  string $id
     * @return Definition
     * @throws ReflectionException
     */
    public function getDefinition(string $id): Definition
    {
        if (!isset($this->definitions[$id])) {
            $this->register($id);
        }

        return $this->definitions[$id];
    }

    /**
     * @inheritDoc
     */
    public function addParameter(string $id, mixed $value): self
    {
        $this->parameters[$id] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getParameter(string $id): mixed
    {
        if (!isset($this->parameters[$id])) {
            throw new NotFoundException();
        }
        return $this->parameters[$id];
    }

    /**
     * @inheritDoc
     * @throws     ReflectionException
     */
    public function get(string $id): object
    {
        if (!$this->has($id)) {
            if (!class_exists($id) && !interface_exists($id)) {
                throw new NotFoundException();
            }

            $instance = $this->getDefinition($id)->newInstance($this);

            if (!$this->getDefinition($id)->isShared()) {
                return $instance;
            }

            $this->instances[$id] = $instance;
        }

        return $this->instances[$id];
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return isset($this->instances[$id]);
    }

    /**
     * @inheritDoc
     */
    public function addAlias(string $id, string $class): self
    {
        $this->aliases[$id] = $class;

        return $this;
    }
}