<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @param Router $router
     * @param Request $request
     *
     * @return Response
     */
    public function index(Router $router, Request $request): Response
    {
        return $this->render('pages/home.html.twig');
    }
}
