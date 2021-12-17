<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Blog\Controller\AbstractController;
use Blog\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FooController
 * @package Tests\Fixtures
 */
class FooController extends AbstractController
{
    public function foo(): Response
    {
        return $this->raw('Hello World!');
    }

    /**
     * @param string $name
     * @return Response
     */
    public function fooWithVar(string $name): Response
    {
        return $this->raw("Hello $name!");
    }

    /**
     * @param string $name
     * @param int $age
     * @return Response
     */
    public function fooWithVars(string $name, int $age): Response
    {
        return $this->raw("Hello $name! You are $age yo");
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function fooWithReq(Request $request): Response
    {
        return $this->raw('Hello for ' . $request->getRequestUri());
    }

    /**
     * @param Request $request
     * @param string $name
     * @return Response
     */
    public function fooWithReqAndVar(Request $request, string $name): Response
    {
        return $this->raw('Hello ' . $name . ' for ' . $request->getRequestUri());
    }

    /**
     * @param Request $request
     * @param string $name
     * @param int $age
     * @return Response
     */
    public function fooWithReqAndVars(Request $request, string $name, int $age): Response
    {
        return $this->raw('Hello ' . $name . ', ' . $age . ' for ' . $request->getRequestUri());
    }


    public function useRouter(Router $router)
    {
        var_dump($router);
        return $this->raw('Hello World!');
    }
}
