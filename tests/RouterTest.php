<?php

declare(strict_types=1);

namespace Blog\Tests;

use Blog\Fixtures\FooController;
use Blog\Fixtures\HomeController;
use Blog\Router\Exceptions\RouteAlreadyExistsException;
use Blog\Router\Exceptions\RouteNotFoundException;
use Blog\Router\Route;
use Blog\Router\Router;
use PHPUnit\Framework\TestCase;

/**
 * Class RouterTest
 * @package Blog\Tests
 */
class RouterTest extends TestCase
{
    public function test()
    {
        $router = new Router();

        $routeHome = new Route(
            'home',
            '/',
            [HomeController::class, 'index']
        );

        $routeArticle = new Route(
            'article',
            '/blog/{id}/{slug}',
            function (string $id, string $slug) {
                return sprintf('%s : %s', $id, $slug);
            }
        );

        $routeFoo = new Route(
            'foo',
            '/foo/{bar}',
            [FooController::class, 'index']
        );

        $router->add($routeHome);
        $router->add($routeArticle);
        $router->add($routeFoo);

        $this->assertCount(3, $router->getRouteCollection());

        $this->assertContainsOnlyInstancesOf(Route::class, $router->getRouteCollection());

        $this->assertEquals($routeHome, $router->get('home'));

        $this->assertEquals($routeHome, $router->match('/'));
        $this->assertEquals($routeArticle, $router->match('/blog/12/mon-article'));
        $this->assertEquals($routeFoo, $router->match('/foo/bar'));

        $this->assertEquals('Hello World!', $router->call('/'));
        $this->assertEquals('12 : mon-article', $router->call('/blog/12/mon-article'));
        $this->assertEquals('bar', $router->call('/foo/bar'));
    }

    public function testIfRouteNotFoundByMatch()
    {
        $router = new Router();
        $this->expectException(RouteNotFoundException::class);
        $router->match('/not_found');
    }

    public function testIfRouteNotFoundByGet()
    {
        $router = new Router();
        $this->expectException(RouteNotFoundException::class);
        $router->get('not_found');
    }

    public function testIfRouteAlreadyExists()
    {
        $router = new Router();
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
}
