<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\Contact;
use Blog\Entity\Login;
use Blog\Form\FormHandler;
use Blog\Repository\PostRepository;
use Blog\Repository\UserRepository;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends AbstractController
{
    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(FormHandler $formHandler, Request $request, PostRepository $postRepository): Response
    {
        // todo pagination
        $posts = $postRepository->findAll();

        $contact = new Contact();

        $form = $formHandler->loadFromRequest($request, $contact);

        if ($form->isSubmitted() && $form->isValid()) {
            // todo send mail and display a flash message
        }

        return $this->render('pages/front/home.html.twig', ['posts' => $posts]);
    }

    public function about(): Response
    {
        return $this->render('pages/front/about.html.twig');
    }

    public function contact(): Response
    {
        return $this->render('pages/front/contact.html.twig');
    }

    public function showSinglePost(): Response
    {
        return $this->render('pages/front/post.html.twig');
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function login(Request $request, FormHandler $formHandler, UserRepository $userRepository): Response
    {
        $login = new Login();
        $form = $formHandler->loadFromRequest($request, $login);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->getUserByUsernameOrEmail($login->getUsername());

            if ($user && $user->comparePassword($login->getPassword())) {
                // Login and redirect
            }

            $form->addValidatorError("Identifiants incorrects, veuillez réessayer");
        }

        return $this->render('pages/front/login.html.twig', ['errors' => $form->getErrors()]);
    }

    public function resetPassword(): Response
    {
        return $this->render('pages/front/reset_password.html.twig');
    }
}
