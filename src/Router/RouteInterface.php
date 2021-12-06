<?php

declare(strict_types=1);

namespace Blog\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RouteInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $path
     *
     * @return bool
     */
    public function test(string $path): bool;

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return mixed
     */
    public function call(Request $request, Response $response): mixed;
}
