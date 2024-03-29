<?php

declare(strict_types=1);

namespace Blog\Router;

use Blog\Router\Exceptions\RouteAlreadyExistsException;
use Blog\Router\Exceptions\RouteNotFoundException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Router
 *
 * @package Blog\Router
 */
final class Router implements RouterInterface
{
    /**
     * @var Route[]
     */
    private array $routes = [];

    /**
     * @return Route[]
     */
    public function getRouteCollection(): array
    {
        return $this->routes;
    }

    /**
     * @param  Request $request
     * @return Route|null
     * @throws RouteNotFoundException
     */
    public function getRouteByRequest(Request $request): ?Route
    {
        return $this->match($request->getRequestUri());
    }

    /**
     * @param string $path
     *
     * @return Route
     * @throws RouteNotFoundException
     */
    public function match(string $path): Route
    {
        foreach ($this->routes as $route) {
            if ($route->test($path)) {
                return $route;
            }
        }

        throw new RouteNotFoundException("Route $path not found");
    }

    /**
     * @param Route $route
     *
     * @return $this
     * @throws RouteAlreadyExistsException
     */
    public function add(Route $route): self
    {
        if ($this->has($route->getName())) {
            throw new RouteAlreadyExistsException(sprintf("Route %s already exists", $route->getName()));
        }

        $this->routes[$route->getName()] = $route;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->routes[$name]);
    }

    /**
     * Generate a route from route name and parameters
     *
     * @param  string       $name
     * @param  array<mixed> $parameters
     * @return string
     * @throws RouteNotFoundException
     */
    public function generateUri(string $name, array $parameters = []): string
    {
        $route = $this->get($name);

        /* Check if the route contains variables */
        $pattern = $route->getPath();

        $hasVars = preg_match_all('/{([^}]*)}/', $pattern, $expectedVars);

        /* No? Just return the route path */
        if (!$hasVars) {
            return $route->getPath();
        }

        // Extracts expected parameter's name (without regex part)
        $expectedKeys = array_map(
            function ($expectedVar) {
                if (str_contains($expectedVar, ':')) {
                    return substr($expectedVar, 0, (int)strpos($expectedVar, ':'));
                }

                return $expectedVar;
            },
            $expectedVars[1]
        );


        // Check that the parameters sent are expected by the route
        foreach ($parameters as $paramName => $paramValue) {
            if (!in_array($paramName, $expectedKeys)) {
                throw new InvalidArgumentException(
                    sprintf('Unknown `%s` parameter for the `%s` route', $paramName, $route->getName())
                );
            }
        }

        return (string)preg_replace_callback(
            '/{([^}]*)}/',
            function ($matches) use ($parameters, $name) {
                $routeVar = $matches[1]; // e.g: name:[a-zA-Z-_ ]+
                $routeVarName = $routeVar;

                // If the param contains a regex, remove it and keep only the parameter's name
                if (str_contains($routeVar, ':')) {
                    $routeVarName = substr($routeVar, 0, (int)strpos($routeVar, ':'));
                }

                if (!array_key_exists($routeVarName, $parameters)) {
                    throw new InvalidArgumentException("Error generating route $name, $routeVarName missing");
                }

                return $parameters[$routeVarName];
            },
            $pattern
        );
    }

    /**
     * @param string $name
     *
     * @return Route
     * @throws RouteNotFoundException
     */
    public function get(string $name): Route
    {
        if (!$this->has($name)) {
            throw new RouteNotFoundException("Route $name not found");
        }

        return $this->routes[$name];
    }
}
