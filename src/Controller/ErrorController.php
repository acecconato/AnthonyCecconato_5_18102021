<?php

declare(strict_types=1);

namespace Blog\Controller;

use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function displayError(): Response
    {
        return $this->render('error500.html.twig');
    }
}
