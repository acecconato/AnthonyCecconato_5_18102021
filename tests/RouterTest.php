<?php

declare(strict_types=1);

namespace Tests;

use Blog\Router\Exceptions\RouteAlreadyExistsException;
use Blog\Router\Exceptions\RouteNotFoundException;
use Blog\Router\Route;
use Blog\Router\Router;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Tests\Fixtures\FooController;
use Tests\Fixtures\HomeController;

/**
 * Class RouterTest
 * @package Blog\Tests
 */
class RouterTest extends TestCase
{
//    public function

    public function test()
    {
        $router = new Router(Request::createFromGlobals());

        $routeHome = new Route(
            'home',
            '/',
            [HomeController::class, 'index']
        );

        $routeFoo = new Route(
            'foo',
            '/foo/{foo}/{bar}',
            [FooController::class, 'index']
        );

        $router->add($routeHome);
        $router->add($routeFoo);

        $this->assertCount(2, $router->getRouteCollection());

        $this->assertContainsOnlyInstancesOf(Route::class, $router->getRouteCollection());

        $this->assertEquals($routeHome, $router->get('home'));

        $this->assertEquals($routeHome, $router->match('/'));
        $this->assertEquals($routeFoo, $router->match('/foo/hello/world'));

        $this->assertEquals('Hello World!', $router->call('/'));
        $this->assertEquals('hello world', $router->call('/foo/hello/world'));
    }

    public function testRouteWithIdAndSlug()
    {
        $router = new Router(Request::createFromGlobals());
        $router->add(
            (new Route(
                'myRoute',
                '/users/{id:\d+}/{slug:[a-zA-Z-_]+}',
                [FooController::class]
            ))
        );

        $this->assertEquals($router->get('myRoute'), $router->match('/users/42/correct-slug'));

        $this->expectException(RouteNotFoundException::class);
        $router->match('/users/uncorrect-id/correct-slug');
    }

    public function testRouteWithVars()
    {
        $router = new Router(Request::createFromGlobals());

        $routeId = new Route(
            'routeId',
            '/users/{id:\d+}',
            [FooController::class, 'getUserById']
        );

        $routeIdAndSlug = new Route(
            'routeIdAndSlug',
            '/slug1/{id:\d+}/{slug}',
            [FooController::class, 'getUserById']
        );

        $routeIdAndSlug2 = new Route(
            'routeIdAndSlug2',
            '/slug2/{id:\d+}/{slug:[a-zA-Z-_]+}',
            [FooController::class, 'getUserById']
        );

        $routeIdAndSlug3 = new Route(
            'routeIdAndSlug3',
            '/users/{id:\d+}/anything/{slug:[a-zA-Z-_]+}',
            [FooController::class, 'getUserById']
        );

        $router->add($routeId);
        $router->add($routeIdAndSlug);
        $router->add($routeIdAndSlug2);
        $router->add($routeIdAndSlug3);

        $this->assertEquals($routeId, $router->match('/users/29'));
        $this->assertEquals($routeIdAndSlug, $router->match('/slug1/29/my-slug-28'));
        $this->assertEquals($routeIdAndSlug2, $router->match('/slug2/29/my-slug-only-alpha'));
        $this->assertEquals($routeIdAndSlug3, $router->match('/users/29/anything/my-slug-only-alpha'));
    }

    public function testIfRouteNotFoundByMatch()
    {
        $router = new Router(Request::createFromGlobals());
        $this->expectException(RouteNotFoundException::class);
        $router->match('/not_found');
    }

    public function testIfRouteNotFoundByGet()
    {
        $router = new Router(Request::createFromGlobals());
        $this->expectException(RouteNotFoundException::class);
        $router->get('not_found');
    }

    public function testIfRouteAlreadyExists()
    {
        $router = new Router(Request::createFromGlobals());
        $router->add(
            new Route(
                'home',
                '/',
                function () {
                }
            )
        );

        $this->expectException(RouteAlreadyExistsException::class);
        $router->add(
            new Route(
                'home',
                '/',
                function () {
                }
            )
        );
    }

    public function testGetRouteByRequest()
    {
        $request = Request::create('https://localhost:8080/users/29');

        $router    = new Router($request);
        $userRoute = new Route('get_user', '/users/{id:\d+}', [FooController::class, 'testRequest']);

        $router->add($userRoute);

        $this->assertEquals($userRoute, $router->getPathByRequest());

        $this->assertEquals('Hello World!', $router->call($router->call()));
    }
}
