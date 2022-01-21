<?php

declare(strict_types=1);

namespace Blog;

use Blog\DependencyInjection\Container;
use Blog\DependencyInjection\ContainerInterface;
use Blog\Router\Router;
use Blog\Router\RouterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;

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
     *
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
     *
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function run(Request $request): void
    {
        $route     = $this->router->match($request->getRequestUri());
        $routeArgs = $route->getArgs($request);

        $callable = $route->getCallable();

        $class  = $callable[0];
        $method = $callable[1];

        $controller = $this->container->get($class::class);

        $reflectionMethod = new ReflectionMethod($controller::class, $method);

        $expectedFromMethod = array_filter(
            $reflectionMethod->getParameters(),
            fn($param) => ! array_key_exists($param->getName(), $routeArgs)
        );

        $methodArgs = [];
        foreach ($expectedFromMethod as $param) {
            if ($this->container->has($param->getName())) {
                $methodArgs[$param->getName()] = $this->container->get($param->getName());
                continue;
            }

            if ($param->getType() !== null) {
                // It's a builtin parameter, or we need to instantiate it?
                if ($this->container->has($param->getName())) {
                    $methodArgs[$param->getName()] = $this->container->get($param->getName());
                }

                // Use the container to instantiate the required class
                if ( ! $param->getType()?->isBuiltin()) {
                    $methodArgs[$param->getName()] = $this->container->get($param->getType()->getName());
                }
            }
        };

        $args = array_merge($routeArgs, $methodArgs);

        $expectedCallOrder = array_map(
            fn($param) => $param->getName(),
            $reflectionMethod->getParameters()
        );

        $args = array_merge(array_flip($expectedCallOrder), $args);

        $response = call_user_func_array([$class, $method], $args);
        $response->send();
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
            ->addParameter('age', 42);
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws DependencyInjection\Exceptions\NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function configureRoutes(): void
    {
        $configRoute = require_once dirname(__DIR__) . '/config/routes.php';

        /** @var Router $router */
        $this->router = $this->container->get(Router::class);

        $configRoute($this->router);
    }
}
