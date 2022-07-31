<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\Login;
use Blog\Entity\User;
use Blog\Form\FormHandler;
use Blog\ORM\EntityManager;
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

            if (!$user || !$user->comparePassword($login->getPassword())) {
                $form->addValidatorError('Identifiants incorrects, veuillez réessayer');
            } elseif (!$user->getEnabled()) {
                $form->addValidatorError("Votre compte n'est pas activé");
            }

            if (!$form->hasErrors()) {
                $authenticator->authenticate($user, (bool)$form->get('rememberMe'));

                /** @var Session $session */
                $session = $request->getSession();
                $session->getFlashBag()->add('success', 'Bonjour, ' . ucfirst($user->getUsername()) . ' !');

                $redirectTo = $request->query->get('redirect') ?? $this->router->generateUri('home');
                return $this->redirect($redirectTo);
            }
        }

        return $this->render(
            'pages/front/login.html.twig',
            [
                'errors' => $form->getErrors(),
                'username' => $form->get('username')
            ]
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws Exception
     */
    public function register(FormHandler $formHandler, Request $request, EntityManager $entityManager): Response
    {
        $user = new User();
        $form = $formHandler->loadFromRequest($request, $user);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword') !== $request->request->get('password_verify')) {
                $form->addValidatorError("Les mots de passe ne correspondent pas");
            }

            if (!$form->hasErrors()) {
                $user
                    ->setPassword(User::encodePassword($form->get('plainPassword')))
                    ->setEnabled(0)
                    ->sanitize();

                $entityManager->add($user);
                $entityManager->flush();

                /** @var Session $session */
                $session = $request->getSession();
                $session->getFlashBag()->add(
                    'success',
                    "Votre demande de création de compte a été prise en compte, vous recevrez un mail lorsque celle-ci sera validée"
                );

                return $this->redirect('/');
            }
        }

        return $this->render('pages/front/register.html.twig', [
            'errors' => $form->getErrors(),
            'form' => $form
        ]);
    }

    public function resetPassword(): Response
    {
        return $this->render('pages/front/reset_password.html.twig');
    }

    /**
     * @throws RouteNotFoundException
     */
    public function logout(Authenticator $authenticator, Request $request): RedirectResponse
    {
        $authenticator->logout();

        /** @var Session $session */
        $session = $request->getSession();
        $session->getFlashBag()->add('success', 'Vous êtes désormais déconnecté');

        return $this->redirect($this->router->generateUri('home'));
    }
}
