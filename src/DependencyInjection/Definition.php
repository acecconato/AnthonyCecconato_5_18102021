<?php

declare(strict_types=1);

namespace Blog\DependencyInjection;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

final class Definition
{
    /**
     * @var ReflectionClass $class
     */
    private ReflectionClass $class;

    /**
     * Definition constructor.
     *
     * @param  string        $id
     * @param  bool          $shared
     * @param  array<string> $aliases
     * @param  array<string> $dependencies
     * @throws ReflectionException
     */
    public function __construct(
        private string $id,
        private bool $shared = true,
        private array $aliases = [],
        private array $dependencies = []
    ) {
        $this->class = new ReflectionClass($id);
    }

    /**
     * @param  bool $shared
     * @return self
     */
    public function setShared(bool $shared): self
    {
        $this->shared = $shared;

        return $this;
    }

    /**
     * @return bool
     */
    public function isShared(): bool
    {
        return $this->shared;
    }

    /**
     * @param  ContainerInterface $container
     * @return object
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function newInstance(ContainerInterface $container): object
    {
        $constructor = $this->class->getConstructor();

        if (null === $constructor) {
            return $this->class->newInstance();
        }

        $parameters = $constructor->getParameters();

        return $this->class->newInstanceArgs(
            array_map(
                function (ReflectionParameter $param) use ($container) {
                    // Todo Q/A : Par quoi remplacer le getClass déprécié ?
                    if ($param->getClass() === null) {
                        return $container->getParameter($param->getName());
                    }

                    return $container->get($param->getType()->getName());
                }, $parameters
            )
        );
    }
}

