<?php

declare(strict_types=1);

namespace Blog\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RouterInterface extends UrlGeneratorInterface, UrlMatcherInterface
{
    /**
     * @return Route[]
     */
    public function getRouteCollection(): array;

    /**
     * @param string $name
     *
     * @return Route
     */
    public function get(string $name): Route;

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
     * @param Request $request
     * @return Response
     */
    public function call(Request $request): Response;

    /**
     * @param Request $request
     * @return Route|null
     */
    public function getRouteByRequest(Request $request): ?Route;
}
