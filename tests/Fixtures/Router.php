<?php

namespace Tests\Fixtures;

use Blog\Router\Route;
use Blog\Router\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Router implements RouterInterface
{
    public function __construct(Foo $foo)
    {
    }

    public function getRouteCollection(): array
    {
        // TODO: Implement getRouteCollection() method.
    }

    public function get(string $name): Route
    {
        // TODO: Implement get() method.
    }

    public function has(string $name): bool
    {
        // TODO: Implement has() method.
    }

    public function add(Route $route): RouterInterface
    {
        // TODO: Implement add() method.
    }

    public function match(string $path): Route
    {
        // TODO: Implement match() method.
    }

    public function call(Request $request): Response
    {
        // TODO: Implement call() method.
    }

    public function getRouteByRequest(Request $request): ?Route
    {
        // TODO: Implement getRouteByRequest() method.
    }

    public function generateUri(string $name, array $parameters = []): string
    {
        // TODO: Implement generateUri() method.
    }
}