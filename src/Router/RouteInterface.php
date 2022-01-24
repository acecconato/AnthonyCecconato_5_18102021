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
     * @return array
     */
    public function getCallable(): array;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getArgs(Request $request): array;
}
