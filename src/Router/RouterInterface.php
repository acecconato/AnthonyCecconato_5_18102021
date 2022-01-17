<?php

declare(strict_types=1);

namespace Blog\Router;

use Symfony\Component\HttpFoundation\Request;

interface RouterInterface extends UrlGeneratorInterface, UrlMatcherInterface
{
    /**
     * @return Route[]
     */
    public function getRouteCollection(): array;

    /**
     * @param Request $request
     *
     * @return Route|null
     */
    public function getRouteByRequest(Request $request): ?Route;

    /**
     * @param string $path
     *
     * @return Route
     */
    public function match(string $path): Route;

    /**
     * @param Route $route
     *
     * @return $this
     */
    public function add(Route $route): self;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param string $name
     *
     * @return Route
     */
    public function get(string $name): Route;
}
