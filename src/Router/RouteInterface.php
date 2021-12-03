<?php

declare(strict_types=1);

namespace Blog\Router;

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
     * @param string $path
     *
     * @return mixed
     */
    public function call(string $path): mixed;
}
