<?php

declare(strict_types=1);

namespace Blog\Router;

use Blog\Router\Exceptions\RouteAlreadyExistsException;
use Blog\Router\Exceptions\RouteNotFoundException;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Router
 * @package Blog\Router
 */
final class Router implements RouterInterface
{
    /**
     * @var Route[]
     */
    private array $routes = [];

    /**
     * Router constructor.
     *
     * @param Request $request
     */
    public function __construct(
        private Request $request,
//        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * @return Route[]
     */
    public function getRouteCollection(): array
    {
        return $this->routes;
    }

    /**
     * @param string $name
     *
     * @return Route|null
     * @throws RouteNotFoundException
     */
    public function get(string $name): ?Route
    {
        if ( ! $this->has($name)) {
            throw new RouteNotFoundException();
        }

        return $this->routes[$name];
    }

    /**
     * @return Route
     * @throws RouteNotFoundException
     */
    public function getRouteByRequest(): Route
    {
        return $this->match($this->request->getRequestUri());
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
     * @param Route $route
     *
     * @return $this
     * @throws RouteAlreadyExistsException
     */
    public function add(Route $route): self
    {
        if ($this->has($route->getName())) {
            throw new RouteAlreadyExistsException();
        }

        $this->routes[$route->getName()] = $route;

        return $this;
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

        throw new RouteNotFoundException();
    }

    /**
     * @param string $path
     *
     * @return mixed
     * @throws RouteNotFoundException
     * @throws ReflectionException
     */
    public function call(string $path): mixed
    {
        $response = new Response();

        return $this->match($path)->call($this->request, $response);
    }

    /**
     * @param Request $request
     *
     * @return $this
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function generateUri(string $name, array $parameters): string
    {
//        $this->urlGenerator->generate($name, $parameters);
    }

    public function getUrlGenerator(): UrlGeneratorInterface
    {
//        return $this->urlGenerator;
    }
}
