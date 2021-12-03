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
    public function index(string $bar, string $foo)
    {
        return "$foo $bar";
    }

    public function getUserById()
    {

    }

    public function testRequest(Request $request, Response $response)
    {
        $response->setContent('Hello World!');
        return $response->send();
    }
}
