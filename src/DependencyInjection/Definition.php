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
    // @phpstan-ignore-next-line
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
        // @phpstan-ignore-next-line
        private string $id,
        private bool $shared = true,
        // @phpstan-ignore-next-line
        private array $aliases = [],
        // @phpstan-ignore-next-line
        private array $dependencies = []
    ) {
        $this->class = new ReflectionClass($id);
    }

    public function setShared(bool $shared): self
    {
        $this->shared = $shared;

        return $this;
    }

    public function isShared(): bool
    {
        return $this->shared;
    }

    /**
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
                    // @phpstan-ignore-next-line
                    if ($param->getType() !== null && $param->getType()->isBuiltin()) {
                        // @phpstan-ignore-next-line
                        return $container->getParameter($param->getName());
                    }

                    // @phpstan-ignore-next-line
                    return $container->get($param->getType()->getName());
                },
                $parameters
            )
        );
    }
}
