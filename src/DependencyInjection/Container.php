<?php

declare(strict_types=1);

namespace Blog\DependencyInjection;

use Blog\DependencyInjection\Exceptions\ContainerException;
use Blog\DependencyInjection\Exceptions\NotFoundException;
use Blog\Router\Router;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
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
     * @throws ReflectionException
     */
    public function register(string $id): self
    {
        $reflectionClass = new ReflectionClass($id);

        if ($reflectionClass->isInterface()) {
            $this->register($this->aliases[$id]);
            $this->definitions[$id] = &$this->definitions[$this->aliases[$id]];

            return $this;
        }

        $dependencies = [];

        if (null !== $reflectionClass->getConstructor()) {
            $dependencies = array_map(
            // @phpstan-ignore-next-line
                fn(ReflectionParameter $parameter) => $this->getDefinition($parameter->getType()->getName()),
                array_filter(
                    $reflectionClass->getConstructor()->getParameters(),
                    function (ReflectionParameter $parameter) {
                        // @phpstan-ignore-next-line
                        if ($parameter->getType() !== null && !$parameter->getType()->isBuiltin()) {
                            // @phpstan-ignore-next-line
                            return new ReflectionClass($parameter->getType()->getName());
                        }

                        return null;
                    }
                )
            );
        }

        $aliases = array_filter($this->aliases, fn(string $alias) => $id === $alias);

        $this->definitions[$id] = new Definition(id: $id, aliases: $aliases, dependencies: $dependencies);

        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public function getDefinition(string $id): Definition
    {
        if (!isset($this->definitions[$id])) {
            $this->register($id);
        }

        return $this->definitions[$id];
    }

    public function addParameter(string $id, mixed $value): self
    {
        $this->parameters[$id] = $value;

        return $this;
    }

    /**
     * @throws NotFoundException
     */
    public function getParameter(string $id): mixed
    {
        if (!isset($this->parameters[$id])) {
            throw new NotFoundException("'$id' required parameter not found");
        }

        return $this->parameters[$id];
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     * @throws NotFoundException
     */
    public function get(string $id): mixed
    {
        if (isset($this->parameters[$id])) {
            return $this->parameters[$id];
        }

        if (!$this->has($id) && !isset($this->parameters[$id])) {
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

    public function has(string $id): bool
    {
        return isset($this->instances[$id]) || isset($this->parameters[$id]);
    }

    public function addAlias(string $id, string $class): self
    {
        $this->aliases[$id] = $class;

        return $this;
    }

    /**
     * @throws ContainerException
     */
    public function registerExisting(object $obj, string $alias = ''): self
    {
        if ($this->has($obj::class)) {
            throw new ContainerException($obj::class . ' is already registered');
        }

        if ($alias) {
            $this->aliases[$alias] = $obj::class;
        }

        $this->definitions[$obj::class] = $obj;
        $this->instances[$alias] = $obj;
        return $this;
    }
}