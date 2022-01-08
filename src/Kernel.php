<?php

declare(strict_types=1);

namespace Blog;

use Blog\Config\ConfigFactory;
use Blog\DependencyInjection\Container;
use Blog\DependencyInjection\ContainerInterface;
use Blog\Router\Router;
use Blog\Router\Router\Exceptions\RouteAlreadyExistsException;
use Blog\Router\RouterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;

// TODO Q/A Comment implémenter l'OptionResolver correctement, et où l'utiliser pour que ce soit pertinent ?

final class Kernel
{
    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * @param string $env
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function __construct(
        private string $env

    ) {
        $this->container = new Container();
        $this->configureContainer();
        $this->configureRoutes();
    }

    /**
     * @param Request $request
     * @return void
     */
    public function run(Request $request): void
    {
        // TODO Q/A Ici on send mais est ce que ce ne serait pas plus simple de récupérer le controller pour y attacher le container
        // et ensuite l'executer ? Ou passer par un Responder ?
       $this->router->call($request)->send();
    }

    /**
     * @return void
     */
    public function configureContainer(): void
    {
        $this->container
            ->addAlias(RouterInterface::class, Router::class);

        $this->container
            ->addParameter('env', $this->env)
            ->addParameter('source_dir', __DIR__)
            ->addParameter('cache_dir', sprintf('%s/../var/cache/%s', __DIR__, $this->env))
            ->addParameter('templates_dir', dirname(__DIR__) . '/templates')
            ->addParameter('config', ConfigFactory::getConfig());
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws DependencyInjection\Exceptions\NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws RouteAlreadyExistsException
     */
    public function configureRoutes(): void
    {
        /** @var Router $router */
        $this->router = $this->container->get(Router::class);

        $routes = $this->container->getParameter('config')['routes'];

        // TODO Q/A Plutôt passer par l'OptionResolver ?
        foreach ($routes as $route) {
            $this->router->add($route);
        }
    }
}
