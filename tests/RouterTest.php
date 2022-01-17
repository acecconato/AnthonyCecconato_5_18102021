<?php

declare(strict_types=1);

namespace Tests;

use Blog\Router\Exceptions\RouteAlreadyExistsException;
use Blog\Router\Exceptions\RouteNotFoundException;
use Blog\Router\Route;
use Blog\Router\Router;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Tests\Fixtures\FooController;

/**
 * Class RouterTest
 * @package Tests
 */
class RouterTest extends TestCase
{
    private const ENDPOINT = 'http://localhost:8080';

    public function testAddRoutes()
    {
        $router = new Router();
        $router->add(new Route('home', '/', [FooController::class, 'index']));

        $this->assertContainsOnlyInstancesOf(Route::class, $router->getRouteCollection());
        $this->assertCount(1, $router->getRouteCollection());

        $router->add(new Route('foo', '/foo', [FooController::class, 'index']));

        $this->assertCount(2, $router->getRouteCollection());

        $this->assertTrue($router->has('home'));
    }

    public function testGetRouteByRequest()
    {
        $request = Request::create(self::ENDPOINT.'/foo');

        $router = new Router();

        $router->add(new Route('home', '/', [FooController::class, 'index']));
        $router->add(new Route('foo', '/foo', [FooController::class, 'index']));

        $this->assertEquals(
            new Route('foo', '/foo', [FooController::class, 'index']),
            $router->getRouteByRequest($request)
        );
    }

    public function testRouteMatch()
    {
        $router = new Router();

        $router->add(new Route('home', '/', [FooController::class, 'index']));
        $router->add(new Route('foo', '/foo', [FooController::class, 'index']));
        $router->add(new Route('foo99', '/foo/{id:\d+}', [FooController::class, 'index']));
        $router->add(new Route('foo99john', '/foo/{id:\d+}/{name}', [FooController::class, 'index']));

        $match = $router->match('/foo');
        $this->assertEquals(new Route('foo', '/foo', [FooController::class, 'index']), $match);

        unset($match);
        $match = $router->match('/foo/99');
        $this->assertEquals(new Route('foo99', '/foo/{id:\d+}', [FooController::class, 'index']), $match);

        unset($match);
        $match = $router->match('/foo/99/john');
        $this->assertEquals(new Route('foo99john', '/foo/{id:\d+}/{name}', [FooController::class, 'index']), $match);

        unset($match);
        $match = $router->match('/foo/99/john');
        $this->assertEquals(new Route('foo99john', '/foo/{id:\d+}/{name}', [FooController::class, 'index']), $match);

        unset($router, $match);
        $router = new Router();
        $router->add(new Route('foo99', '/foo/{id}', [FooController::class, 'index']));
        $router->add(new Route('foo99john', '/foo/{id}/{name:\w+}', [FooController::class, 'index']));

        $match = $router->match('/foo/99/john');
        $this->assertEquals(new Route('foo99john', '/foo/{id}/{name:\w+}', [FooController::class, 'index']), $match);
    }

    public function testGenerateUri()
    {
        $router = new Router();

        $router->add(new Route('home', '/', [FooController::class, 'index']));
        $router->add(new Route('foo', '/foo', [FooController::class, 'index']));
        $router->add(new Route('foo_with_id', '/foo/{id}', [FooController::class, 'index']));

        $this->assertEquals('/foo', $router->generateUri('foo'));
        $this->assertEquals('/foo/99', $router->generateUri('foo_with_id', ['id' => 99]));
    }

    public function testRouteAlreadyExistsException()
    {
        $router = new Router();

        $route = new Route('home', '/', [FooController::class, 'index']);

        $router->add($route);

        $this->expectException(RouteAlreadyExistsException::class);
        $router->add($route);
    }

    public function testRouteNotFoundException()
    {
        $router = new Router();

        $router->add(new Route('home', '/', [FooController::class, 'index']));

        $this->expectException(RouteNotFoundException::class);
        $router->match(self::ENDPOINT . '/notfound');
    }

    public function testRouteNotFoundExceptionWithReq()
    {
        $router = new Router();

        $router->add(new Route('home', '/{id:\d+}', [FooController::class, 'index']));

        $this->expectException(RouteNotFoundException::class);
        $router->match(self::ENDPOINT . '/notfound');
    }

    public function testGenerateUriInvalidArgumentException()
    {
        $router = new Router();

        $router->add(new Route('foo_with_id', '/foo/{id}', [FooController::class, 'index']));

        $this->expectException(InvalidArgumentException::class);
        $router->generateUri('foo_with_id', ['id' => 99, 'unknow' => 0]);
    }
}
