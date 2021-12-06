<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FooController
 * @package Tests\Fixtures
 */
class FooController
{
    /**
     * @param string $bar
     * @param string $foo
     * @param Response $response
     *
     * @return Response
     */
    public function index(string $bar, string $foo, Response $response): Response
    {
        $response->setContent("$foo $bar");

        return $response->send();
    }

    /**
     * @param Response $response
     *
     * @return Response
     */
    public function foo(Response $response): Response
    {
        $response->setContent('Hello World!');

        return $response->send();
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param Response $response
     *
     * @return Response
     */
    public function getUserFullname(string $firstname, string $lastname, Response $response): Response
    {
        $response->setContent("Hello $firstname $lastname!");

        return $response->send();
    }

    /**
     * @param int $id
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     */
    public function testRequest(int $id, Request $request, Response $response): Response
    {
        $response->setContent('Hello World!');

        return $response->send();
    }
}
