<?php

declare(strict_types=1);

namespace Blog;

use Blog\DependencyInjection\ContainerInterface;
use Blog\Router\Router;
use Blog\Router\RouterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Kernel
{
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    /**
     * @param  ContainerInterface $container
     * @param  array              $config
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        private ContainerInterface $container,
        private array $config = [],
    ) {
        $this->config['routes'] = include_once '../config/routes.php';

        /* TODO Q/A Il me semble que par convention, le constructeur doit normalement être utilisé que pour set
         des variables, est-ce que je peux placer le init ici, ou vaut-il mieux l'appeler ailleurs, comme dans mon
         index.php par exemple ? */
        $this->init();
    }

    public function run(Request $request): Response
    {
        return $this->router->call($request);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function init(): void
    {
        $this->configureRouter();

        foreach ($this->config['routes'] as $route) {
            $this->router->add($route);
        }
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function configureRouter(): void
    {
        $this->container->addAlias(RouterInterface::class, Router::class);

        $this->router = $this->container->get(Router::class);
    }
}
