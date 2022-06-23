<?php

declare(strict_types=1);

namespace Tests;

use Blog\Controller\AbstractController;
use Blog\Controller\FrontController;
use Blog\DependencyInjection\Container;
use Blog\Router\Router;
use Blog\Router\RouterInterface;
use Blog\Templating\Templating;
use Blog\Templating\TemplatingInterface;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Database;
use Tests\Fixtures\Foo;

class ContainerTest extends TestCase
{
    public function test()
    {
        $container = new Container();

        $container
            ->addAlias(RouterInterface::class, Router::class)
            ->addAlias(TemplatingInterface::class, Templating::class);

        $_ENV['APP_ENV'] = 'tests';
        $_ENV['TWIG_DEBUG'] = false;

        $container->addParameter('dbName', 'DB NAME');
        $container->addParameter('dbUser', 'DB USER');
        $container->addParameter('cacheDir', dirname(__DIR__).'/var/cache/tests');
        $container->addParameter('templatesDirs', dirname(__DIR__).'/templates');

        $container->getDefinition(Foo::class)->setShared(false);

        $database1 = $container->get(Database::class);
        $database2 = $container->get(Database::class);

        $this->assertInstanceOf(Database::class, $database1);
        $this->assertInstanceOf(Database::class, $database2);

        $this->assertEquals(spl_object_id($database1), spl_object_id($database2));

        $this->assertInstanceOf(Router::class, $container->get(RouterInterface::class));

        $foo1 = $container->get(Foo::class);
        $foo2 = $container->get(Foo::class);

        $this->assertNotEquals(spl_object_id($foo1), spl_object_id($foo2));

        $controller = $container->get(FrontController::class);

        $this->assertInstanceOf(AbstractController::class, $controller);
    }
}
