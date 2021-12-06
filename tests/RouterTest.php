<?php

declare(strict_types=1);

namespace Tests;

use Blog\Router\Exceptions\RouteAlreadyExistsException;
use Blog\Router\Exceptions\RouteNotFoundException;
use Blog\Router\Route;
use Blog\Router\Router;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Fixtures\FooController;

/**
 * Class RouterTest
 * @package Tests
 */
class RouterTest extends TestCase
{
    const ENDPOINT = 'http://localhost:8080';

    /**
     * @throws RouteAlreadyExistsException
     * @throws RouteNotFoundException
     * @throws ReflectionException
     */
    public function testHomeRoute()
    {
        $request  = Request::create(self::ENDPOINT);
        $response = new Response('Hello World!');

        $router = new Router($request);

        $routeHome = new Route(
            'home',
            '/',
            [FooController::class, 'foo']
        );

        $router->add($routeHome);

        $this->assertEquals($routeHome, $router->get('home'));

        $this->assertEquals($routeHome, $router->match($request->getRequestUri()));

        $this->assertEquals($response, $router->call($request->getRequestUri()));
    }

    public function testMultipleRoutes()
    {
        $request  = Request::create(self::ENDPOINT.'/users/John/Doe');
        $response = new Response('Hello John Doe!');

        $router = new Router($request);

        $routeHome = new Route(
            'home',
            '/',
            [FooController::class, 'foo']
        );

        $router->add($routeHome);

        $this->assertCount(1, $router->getRouteCollection());

        $routeGetUsers = new Route(
            'get_users',
            '/users',
            [FooController::class, 'foo']
        );

        $router->add($routeGetUsers);

        $this->assertCount(2, $router->getRouteCollection());

        $routeGetUserFullname = new Route(
            'get_user',
            '/users/{firstname:\w+}/{lastname:\w+}',
            [FooController::class, 'getUserFullname']
        );

        $router->add($routeGetUserFullname);

        $this->assertCount(3, $router->getRouteCollection());

        $this->assertContainsOnlyInstancesOf(Route::class, $router->getRouteCollection());

        $this->assertEquals($response, $router->call($request->getRequestUri()));
    }

    public function testIncorrectRouteArgsException()
    {
        $digitsRequest = Request::create(self::ENDPOINT.'/this-is-not-digits');

        $digitsRoute = new Route(
            'expect_digits',
            '/{digits:\d+}',
            [FooController::class, 'foo']
        );

        $router = new Router($digitsRequest);
        $router->add($digitsRoute);

        $this->expectException(RouteNotFoundException::class);
        $router->match($digitsRequest->getRequestUri());
    }

    /**
     * @throws RouteNotFoundException
     */
    public function testIfRouteNotFoundByMatch()
    {
        $router = new Router(Request::createFromGlobals());
        $this->expectException(RouteNotFoundException::class);
        $router->match('/not_found');
    }

    /**
     * @throws RouteNotFoundException
     */
    public function testIfRouteNotFoundByGet()
    {
        $router = new Router(Request::createFromGlobals());
        $this->expectException(RouteNotFoundException::class);
        $router->get('not_found');
    }

    /**
     * @throws RouteAlreadyExistsException
     */
    public function testRouteAlreadyExistsException()
    {
        $router = new Router(Request::createFromGlobals());
        $router->add(
            new Route(
                'home',
                '/',
                [FooController::class, 'foo']
            )
        );

        $this->expectException(RouteAlreadyExistsException::class);
        $router->add(
            new Route(
                'home',
                '/',
                [FooController::class, 'foo']
            )
        );
    }

    /**
     * @throws ReflectionException
     * @throws RouteAlreadyExistsException
     * @throws RouteNotFoundException
     */
    public function testGetRouteByRequest()
    {
        $request = Request::create('https://localhost:8080/users/29');

        $router    = new Router($request);
        $userRoute = new Route('get_user', '/users/{id:\d+}', [FooController::class, 'testRequest']);

        $router->add($userRoute);

        $this->assertEquals($userRoute, $router->getRouteByRequest());

        $exceptedResponse = new Response('Hello World!');
        $this->assertEquals($exceptedResponse, $router->call($request->getRequestUri()));

        $this->expectException(RouteNotFoundException::class);
        $router->match((Request::create(self::ENDPOINT.'/users/abc'))->getRequestUri());
    }
}
