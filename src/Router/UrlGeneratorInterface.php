<?php

declare(strict_types=1);

namespace Blog\Router;

interface UrlGeneratorInterface
{
    public function generate(string $name, array $parameters = []): string;
}
