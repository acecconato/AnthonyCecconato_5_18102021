<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\User;
use Blog\Form\FormHandler;
use Exception;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function index(Request $request, FormHandler $formHandler): Response
    {
        $form = $formHandler->loadFromRequest($request, User::class);

        if ($form->isSubmitted() && $form->isValid()) {
            dd("Submitted & isValid");
        }

        return $this->render(
            'pages/home.html.twig',
            [
                'username' => $form->get('username'),
                'email' => $form->get('email'),
                'csrfToken' => $form->getCsrfToken(),
                'errors' => $form->getErrors()
            ]
        );
    }
}
