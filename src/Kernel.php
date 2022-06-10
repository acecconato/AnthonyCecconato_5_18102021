<?php

declare(strict_types=1);

namespace Blog;

use Blog\DependencyInjection\Container;
use Blog\DependencyInjection\ContainerInterface;
use Blog\Router\Router;
use Blog\Router\RouterInterface;
use Blog\Templating\Templating;
use Blog\Templating\TemplatingInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Throwable;

final class Kernel
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /** @var RouterInterface */
    private RouterInterface $router;

    /** @var ContainerInterface */
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
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function run(Request $request, SessionInterface $session): void
    {
        $request->setSession($session);

        $route = $this->router->match($request->getRequestUri());
        $routeArgs = $route->getArgs($request);

        $callable = $route->getCallable();
        $class = $this->container->get($callable[0]);
        $method = $callable[1];

        $reflectionMethod = new ReflectionMethod($class::class, $method);

        $expectedFromMethod = array_filter(
            $reflectionMethod->getParameters(),
            fn($param) => !array_key_exists($param->getName(), $routeArgs)
        );

        $methodArgs = [];
        foreach ($expectedFromMethod as $param) {
            if ($this->container->has($param->getName())) {
                $methodArgs[$param->getName()] = $this->container->get($param->getName());
                continue;
            }

            if ($param->getType() !== null) {
                // It's a builtin parameter, or we need to instantiate it?
                // @phpstan-ignore-next-line
                if ($this->container->has($param->getName())) {
                    $methodArgs[$param->getName()] = $this->container->get($param->getName());
                }

                // Use the container to instantiate the required class
                // @phpstan-ignore-next-line
                if (!$param->getType()?->isBuiltin()) {
                    // @phpstan-ignore-next-line
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

        try {
            $response = call_user_func_array([$class, $method], $args);
            $response->send();
        } catch (Throwable $e) {
            if ($this->env === 'development') {
                dd($e);
                // Todo call a 500 controller
            }

            exit('Internal server error: 500');
        }
    }

    /**
     * @return void
     * @throws DependencyInjection\Exceptions\ContainerException
     */
    public function configureContainer(): void
    {
        $this->container
            ->addAlias(RouterInterface::class, Router::class)
            ->addAlias(TemplatingInterface::class, Templating::class);

        $this->container
            ->addParameter('env', $this->env)
            ->addParameter('sourceDir', __DIR__)
            ->addParameter('cacheDir', sprintf('%s/../var/cache/%s', __DIR__, $this->env))
            ->addParameter('templatesDirs', dirname(__DIR__) . '/templates');

        $this->container->registerExisting($this->container, ContainerInterface::class);
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
        $configRoute = require dirname(__DIR__) . '/config/routes.php';

        $this->router = $this->container->get(Router::class);

        $configRoute($this->router);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
