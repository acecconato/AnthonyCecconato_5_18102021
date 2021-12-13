<?php

declare(strict_types=1);

namespace Blog\Container;

use Blog\Container\Exceptions\NotFoundException;
use Closure;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private array $definitions;

    /**
     * @var array
     */
    private array $resolvedEntries = [];


    /**
     * @param array $definitions
     */
    public function __construct(array $definitions)
    {
        $this->definitions = array_merge($definitions, [ContainerInterface::class => $this]);
    }

    /**
     * @param string $index
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function get(string $index): mixed
    {
        if ( ! $this->has($index)) {
            throw new NotFoundException("No entry or class found for $index");
        }

        if (array_key_exists($index, $this->resolvedEntries)) {
            return $this->resolvedEntries[$index];
        }

        $value = $this->definitions[$index];
        if ($value instanceof Closure) {
            $value = $value($this);
        }

        return $value;
    }

    /**
     * @param string $index
     *
     * @return bool
     */
    public function has(string $index): bool
    {
        return array_key_exists($index, $this->definitions) || array_key_exists($index, $this->resolvedEntries);
    }
}
