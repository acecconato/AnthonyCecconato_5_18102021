<?php

declare(strict_types=1);

namespace Blog\Router;

use ArrayIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

final class Router
{
    private const NO_ROUTE = 404;

    public function __construct(
        private ArrayIterator $routes,
        private UrlGenerator $urlGenerator
    ) {
        foreach ($this->routes as $route) {
            $this->add($route);
        }
    }

    /** Add a new route to the router
     *
     * @param Route $route The route to add
     *
     * @return $this
     */
    private function add(Route $route): self
    {
        $this->routes->offsetSet($route->getName(), $route);

        return $this;
    }

    public function match(Request $request): Route
    {

    }

    public function matchFromPath(string $path, string $method): Route
    {

    }

    public function generateUri(string $name, $parameters = []): string
    {

    }

    public function getUrlGenerator(): UrlGenerator
    {
        return $this->urlGenerator;
    }
}
