<?php

declare(strict_types=1);

namespace Blog;

use Blog\Controller\Exception\UnauthorizedException;
use Blog\Controller\ErrorController;
use Blog\Database\Adapter\AdapterInterface;
use Blog\Database\Adapter\MySQLAdapter;
use Blog\DependencyInjection\Container;
use Blog\DependencyInjection\ContainerInterface;
use Blog\Event\Dispatcher\EventDispatcher;
use Blog\Event\EventListener\ListenerProvider;
use Blog\Event\PreRequestHandlingEvent;
use Blog\ORM\Mapping\DataMapper;
use Blog\ORM\Mapping\MapperInterface;
use Blog\Router\Router;
use Blog\Router\RouterInterface;
use Blog\Templating\Templating;
use Blog\Templating\TemplatingInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Throwable;

final class Kernel
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';

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
        $this->configureEvents();
    }

    /**
     * @throws DependencyInjection\Exceptions\ContainerException
     * @throws NotFoundExceptionInterface
     * @throws DependencyInjection\Exceptions\NotFoundException
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    private function configureEvents(): void
    {
        $registerEvents = include dirname(__DIR__) . '/config/registerEvents.php';

        /**
         * @var ListenerProvider $listenerProvider
        */
        $listenerProvider = $this->container->get(ListenerProvider::class);

        $registerEvents($listenerProvider);

        $eventDispatcher = new EventDispatcher($listenerProvider, $this->container);
        $this->container->registerExisting($eventDispatcher);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function run(): void
    {
        /**
         * @var Request $request
        */
        $request = $this->container->get(Request::class);

        $request->setSession(new Session());

        /**
         * @var EventDispatcher $eventDispatcher
        */
        $eventDispatcher = $this->container->get(EventDispatcher::class);
        $eventDispatcher->dispatch(new PreRequestHandlingEvent($request));

        $route = $this->router->match($request->getRequestUri());
        $routeArgs = $route->getArgs($request);

        $callable = $route->getCallable();
        $class    = $this->container->get($callable[0]);
        $method   = $callable[1];

        $reflectionMethod = new ReflectionMethod($class::class, $method);

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
                // @phpstan-ignore-next-line
                if ($this->container->has($param->getName())) {
                    $methodArgs[$param->getName()] = $this->container->get($param->getName());
                    $methodArgs[$param->getName()] = $this->container->get($param->getName());
                }

                // Use the container to instantiate the required class
                // @phpstan-ignore-next-line
                if (! $param->getType()?->isBuiltin()) {
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
            }

            $errorController = $this->container->get(ErrorController::class);

            if ($e::class === UnauthorizedException::class) {
                $response = call_user_func([$errorController, 'redirect'], $this->router->generateUri('login'));
                $response->send();
                return;
            }

            $response = call_user_func([$errorController, 'displayError']);
            $response->send();
        }
    }

    /**
     * @throws ReflectionException
     * @throws DependencyInjection\Exceptions\ContainerException
     */
    public function configureContainer(): void
    {
        $this->container
            ->addAlias(RouterInterface::class, Router::class)
            ->addAlias(TemplatingInterface::class, Templating::class)
            ->addAlias(AdapterInterface::class, MySQLAdapter::class)
            ->addAlias(MapperInterface::class, DataMapper::class);

        $this->container
            ->addParameter('env', $this->env)
            ->addParameter('sourceDir', __DIR__)
            ->addParameter('cacheDir', sprintf('%s/../var/cache/%s', __DIR__, $this->env))
            ->addParameter('templatesDirs', dirname(__DIR__) . '/templates')
            ->addParameter('publicDir', dirname(__DIR__) . '/public')
            ->addParameter('uploadDir', dirname(__DIR__) . '/public/uploads');

        // Register Symfony Mailer
        $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
        $mailer    = new Mailer($transport);

        $this->container->registerExisting($mailer, MailerInterface::class);

        if ($this->env !== 'test') {
            $this->container
                ->addParameter('host', $_ENV['DB_HOST'])
                ->addParameter('dbName', $_ENV['DB_NAME'])
                ->addParameter('dbUser', $_ENV['DB_USER'])
                ->addParameter('dbPassword', $_ENV['DB_PASSWORD']);
        }

        // Register the request so we can retrieve the same instance everywhere through the container
        $request = Request::createFromGlobals();
        $this->container->registerExisting($request, Request::class);

        // Same as the request
        $this->container->registerExisting($this->container, ContainerInterface::class);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws DependencyInjection\Exceptions\NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function configureRoutes(): void
    {
        $configRoute = include dirname(__DIR__) . '/config/routes.php';

        $this->router = $this->container->get(Router::class);

        $configRoute($this->router);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
