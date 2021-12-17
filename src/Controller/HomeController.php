<?php

declare(strict_types=1);

namespace Blog\Controller;

use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->raw('Hello World!');
    }
}
