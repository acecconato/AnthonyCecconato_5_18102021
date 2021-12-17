<?php

declare(strict_types=1);

namespace Tests;

<<<<<<< Updated upstream
use Blog\Container\Container;
use Blog\Container\Exceptions\NotFoundException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testContainer()
    {
        $services = [
            'my.service'   => fn($test) => 'Hello',
            'my.parameter' => 42,
        ];

        $container = new Container($services);

        $this->assertEquals('Hello', $container->get('my.service'));
        $this->assertEquals(42, $container->get('my.parameter'));

        $this->expectException(NotFoundException::class);
        $container->get('unknown');
=======
use Blog\DependencyInjection\Container;
use Blog\DependencyInjection\Exceptions\NotFoundException;
use Blog\Router\RouterInterface;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\Database;
use Tests\Fixtures\Foo;
use Tests\Fixtures\Router;

class ContainerTest extends TestCase
{

    public function test()
    {
        $container = new Container();

        $container->addAlias(RouterInterface::class, Router::class);

        $container->addParameter('dbName', 'DB NAME');
        $container->addParameter('dbUser', 'DB USER');

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
>>>>>>> Stashed changes
    }
}
