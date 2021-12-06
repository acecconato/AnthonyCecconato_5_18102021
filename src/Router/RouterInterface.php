<?php

declare(strict_types=1);

namespace Blog\Router;

use Symfony\Component\HttpFoundation\Request;

interface RouterInterface
{
    /**
     * @return Route[]
     */
    public function getRouteCollection(): array;

    /**
     * @param string $name
     *
     * @return Route|null
     */
    public function get(string $name): ?Route;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param Route $route
     *
     * @return $this
     */
    public function add(Route $route): self;

    /**
     * @param string $path
     *
     * @return Route
     */
    public function match(string $path): Route;

    /**
     * @param string $path
     *
     * @return mixed
     */
    public function call(string $path): mixed;

    /**
     * @return Route
     */
    public function getRouteByRequest(): Route;

    /**
     * @param Request $request
     *
     * @return $this
     */
    public function setRequest(Request $request): self;
}
