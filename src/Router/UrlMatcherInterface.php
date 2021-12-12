<?php

declare(strict_types=1);

namespace Blog\Router;

interface UrlMatcherInterface
{
    /**
     * @param string $path
     *
     * @return Route
     */
    public function match(string $path): Route;
}
