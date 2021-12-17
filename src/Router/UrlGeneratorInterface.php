<?php

declare(strict_types=1);

namespace Blog\Router;

interface UrlGeneratorInterface
{
    /**
     * Generate a route from route name and parameters
     *
     * @param  string                $name
     * @param  array<string, string> $parameters
     * @return string
     */
    public function generateUri(string $name, array $parameters = []): string;
}
