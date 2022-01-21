<?php

namespace Blog\DependencyInjection;

use Blog\DependencyInjection\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface as BaseContainerInterface;
use ReflectionException;

interface ContainerInterface extends BaseContainerInterface
{

    /**
     * 
     * Register a new definition
     *
     * @param  string $id
     * @return $this
     */
    public function register(string $id): self;

    /**
     * 
     * Get a definition
     *
     * @param  string $id
     * @return Definition
     */
    public function getDefinition(string $id): Definition;
    /**
     * @param  string $id
     * @param  mixed  $value
     * @return $this
     */
    public function addParameter(string $id, mixed $value): self;

    /**
     * @param  string $id
     * @return mixed
     */
    public function getParameter(string $id): mixed;

    /**
     * @param  string $id
     * @param  string $class
     * @return $this
     */
    public function addAlias(string $id, string $class): self;
}
