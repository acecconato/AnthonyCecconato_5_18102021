<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\Login;
use Blog\Form\FormHandler;
use Blog\Repository\UserRepository;
use Blog\Router\Exceptions\RouteNotFoundException;
use Blog\Security\Authenticator;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class SecurityController extends AbstractController
{
    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function login(
        Request $request,
        FormHandler $formHandler,
        UserRepository $userRepository,
        Authenticator $authenticator
    ): Response {
        $login = new Login();
        $form = $formHandler->loadFromRequest($request, $login);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->getUserByUsernameOrEmail($login->getUsername());

            if ($user && $user->comparePassword($login->getPassword())) {
                $authenticator->authenticate($user);

                /** @var Session $session */
                $session = $request->getSession();
                $session->getFlashBag()->add('success', 'Bonjour, ' . ucfirst($user->getUsername()) . ' !');
                return $this->redirect($this->router->generateUri('home'));
            }

            $form->addValidatorError("Identifiants incorrects, veuillez réessayer");
        }

        return $this->render('pages/front/login.html.twig', ['errors' => $form->getErrors()]);
    }

    public function resetPassword(): Response
    {
        return $this->render('pages/front/reset_password.html.twig');
    }

    /**
     * @throws RouteNotFoundException
     */
    public function logout(): RedirectResponse
    {
        return $this->redirect($this->router->generateUri('home'));
    }
}