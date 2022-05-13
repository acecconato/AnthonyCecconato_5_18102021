<?php

declare(strict_types=1);

namespace Blog\Controller;

use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('pages/home.html.twig');
    }

    public function about(): Response
    {
        return $this->render('pages/about.html.twig');
    }

    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig');
    }

    public function showSinglePost(): Response
    {
        return $this->render('pages/post.html.twig');
    }

    public function login(): Response
    {
        return $this->render('pages/login.html.twig');
    }

    public function resetPassword(): Response
    {
        return $this->render('pages/reset_password.html.twig');
    }
}
