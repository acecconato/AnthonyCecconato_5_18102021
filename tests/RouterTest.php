<?php

declare(strict_types=1);

namespace Tests;

use Blog\Router\Exceptions\RouteAlreadyExistsException;
use Blog\Router\Exceptions\RouteNotFoundException;
use Blog\Router\Route;
use Blog\Router\Router;
use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
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
    private const ENDPOINT = 'http://localhost:8080';

    /**
     * @return void
     * @throws RouteAlreadyExistsException
     */
    public function testAddRoutes(): void
    {
        $router = new Router();

        foreach ($this->provideRoutesData() as $route) {
            $router->add($route);
        }

        $this->assertCount(6, $router->getRouteCollection());
        $this->assertContainsOnlyInstancesOf(Route::class, $router->getRouteCollection());
    }

    /**
     * @return Route[]
     */
    #[Pure] private function provideRoutesData(): array
    {
        $homeRoute = new Route(
            'home',
            '/',
            [FooController::class, 'foo']
        );

        $getUsersRoute = new Route(
            'get_users',
            '/users',
            [FooController::class, 'foo']
        );

        $getUserByNameRoute = new Route(
            'get_user_by_name',
            '/users/{name:[a-zA-Z-_ ]+}',
            [FooController::class, 'fooWithVar']
        );

        $getUserByNameAndReqRoute = new Route(
            'get_user_by_name_req',
            '/users/req/{name:[a-zA-Z-_ ]+}',
            [FooController::class, 'fooWithReqAndVar']
        );

        $getUsernameAndAge = new Route(
            'get_username_age',
            '/users/{name:[a-zA-Z-_ ]+}/{age:\d+}',
            [FooController::class, 'fooWithVars']
        );

        $getUsernameAndAgeWithReq = new Route(
            'get_username_age_req',
            '/users/req/{name:[a-zA-Z-_ ]+}/{age:\d+}',
            [FooController::class, 'fooWithReqAndVars']
        );

        return [
            $homeRoute,
            $getUsersRoute,
            $getUserByNameRoute,
            $getUserByNameAndReqRoute,
            $getUsernameAndAge,
            $getUsernameAndAgeWithReq,
        ];
    }

    /**
     * @return void
     * @throws RouteAlreadyExistsException
     */
    public function testRouteAlreadyExistsException(): void
    {
        $router = $this->generateRouter();

        $alreadyExistsRoute = new Route(
            'home',
            '/',
            [FooController::class, 'foo']
        );

        $this->expectException(RouteAlreadyExistsException::class);
        $router->add($alreadyExistsRoute);
    }

    /**
     * @return Router
     * @throws RouteAlreadyExistsException
     */
    private function generateRouter(): Router
    {
        $router = new Router();

        foreach ($this->provideRoutesData() as $route) {
            $router->add($route);
        }

        return $router;
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws RouteAlreadyExistsException
     * @throws RouteNotFoundException
     */
    public function testSimpleResponse(): void
    {
        $request = Request::create(self::ENDPOINT);

        $router = $this->generateRouter();

        $this->assertEquals((new Response('Hello World!')), $router->call($request));
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws RouteAlreadyExistsException
     * @throws RouteNotFoundException
     */
    public function testResponseWithArg(): void
    {
        $request = Request::create(self::ENDPOINT . '/users/john');

        $router = $this->generateRouter();

        $this->assertEquals((new Response('Hello john!')), $router->call($request));

        $this->expectException(RouteNotFoundException::class);
        $request = Request::create(self::ENDPOINT . '/users/42');
        $router->call($request);
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws RouteAlreadyExistsException
     * @throws RouteNotFoundException
     */
    public function testResponseWithArgAndReq(): void
    {
        $request = Request::create(self::ENDPOINT . '/users/req/john');

        $router = $this->generateRouter();

        $this->assertEquals((new Response('Hello john for /users/req/john')), $router->call($request));

        $this->expectException(RouteNotFoundException::class);
        $request = Request::create(self::ENDPOINT . '/users/42');
        $router->call($request);
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws RouteAlreadyExistsException
     * @throws RouteNotFoundException
     */
    public function testResponseWithArgs()
    {
        $request = Request::create(self::ENDPOINT . '/users/john/42');

        $router = $this->generateRouter();

        $this->assertEquals((new Response('Hello john! You are 42 yo')), $router->call($request));

        $this->expectException(RouteNotFoundException::class);
        $request = Request::create(self::ENDPOINT . '/users/john/invalid');
        $router->call($request);
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws RouteAlreadyExistsException
     * @throws RouteNotFoundException
     */
    public function testResponseWithArgsAndReq()
    {
        $request = Request::create(self::ENDPOINT . '/users/req/john/42');

        $router = $this->generateRouter();

        $this->assertEquals((new Response('Hello john, 42 for /users/req/john/42')), $router->call($request));

        $this->expectException(RouteNotFoundException::class);
        $request = Request::create(self::ENDPOINT . '/users/req/0/42');
        $router->call($request);
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws RouteAlreadyExistsException
     * @throws RouteNotFoundException
     */
    public function testIncorrectRouteArgsException(): void
    {
        $router = $this->generateRouter();

        $request = Request::create(self::ENDPOINT . '/users/42'); // excepts [a-zA-Z-_ ]+ instead of 42

        $this->expectException(RouteNotFoundException::class);
        $router->call($request);
    }

    /**
     * @return void
     * @throws RouteAlreadyExistsException
     * @throws RouteNotFoundException
     */
    public function testGenerateUri()
    {
        $router = $this->generateRouter();

        $this->assertEquals('/', $router->generateUri('home'));

        $this->assertEquals(
            '/users/john/42',
            $router->generateUri('get_username_age', ['name' => 'john', 'age' => 42])
        );

        $this->assertEquals('/users/john', $router->generateUri('get_user_by_name', ['name' => 'john']));
    }

    /**
     * @return void
     * @throws RouteAlreadyExistsException
     * @throws RouteNotFoundException
     */
    public function testGenerateUriInvalidArgumentException()
    {
        $router = $this->generateRouter();

        $this->expectException(InvalidArgumentException::class);
        $router->generateUri('get_user_by_name', ['name' => 'John', 'invalid' => 42]);
    }
}
