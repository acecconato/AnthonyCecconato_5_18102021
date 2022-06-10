<?php

namespace Blog\DependencyInjection;

use Blog\DependencyInjection\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface as BaseContainerInterface;
use ReflectionException;

interface ContainerInterface extends BaseContainerInterface
{
    public function register(string $id): self;

    public function getDefinition(string $id): Definition;

    public function addParameter(string $id, mixed $value): self;

    public function getParameter(string $id): mixed;

    public function addAlias(string $id, string $class): self;

    public function registerExisting(object $obj, string $alias = ''): self;
}
