<?php

declare(strict_types=1);

namespace Blog\Twig;

use Blog\Router\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PathExtension extends AbstractExtension
{
    public function __construct(private RouterInterface $router)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('path', [$this, 'getPath'])
        ];
    }

    /**
     * @param string $name
     * @param array<mixed> $parameters
     * @return string
     */
    public function getPath(string $name, array $parameters = []): string
    {
        return $this->router->generateUri($name, $parameters);
    }
}