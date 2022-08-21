<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Entity\HandleResetPassword;
use Blog\Entity\Login;
use Blog\Entity\ResetPassword;
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
use Soundasleep\Html2Text;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Throwable;

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
        Authenticator $auth
    ): Response {
        $this->denyAccessUnlessNotLoggedIn();

        $login = new Login();
        $form  = $formHandler->loadFromRequest($request, $login);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->getUserByUsernameOrEmail($login->getUsername());

            if (! $user || ! $user->comparePassword($login->getPassword())) {
                $form->addValidatorError('Identifiants incorrects, veuillez réessayer');
            } elseif (! $user->getEnabled()) {
                $form->addValidatorError("Votre compte n'est pas activé");
            }

            if (! $form->hasErrors()) {
                $auth->authenticate($user, (bool)$form->get('rememberMe'));

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
    public function register(
        FormHandler $formHandler,
        Request $request,
        EntityManager $entityManager,
    ): Response {
        $this->denyAccessUnlessNotLoggedIn();

        $user = new User();
        $form = $formHandler->loadFromRequest($request, $user);

        if ($form->isSubmitted() && $form->isValid()) {
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

        return $this->render('pages/front/register.html.twig', ['form' => $form]);
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function resetPassword(
        FormHandler $formHandler,
        Request $request,
        UserRepository $userRepository,
        MailerInterface $mailer,
    ): Response {
        $this->denyAccessUnlessNotLoggedIn();

        $resetPassword = new ResetPassword();
        $form          = $formHandler->loadFromRequest($request, $resetPassword);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User|false $user */
            $user = $userRepository->getUserByUsernameOrEmail($resetPassword->getUsername());

            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->add('success', "Un mail vous a été envoyé à l'adresse associée au compte");

            if (! $user) {
                // Shadow error, redirect but do nothing
                return $this->redirect($this->router->generateUri('login'));
            }

            if (! $form->hasErrors()) {
                $token = md5(uniqid());

                $user->setResetToken($token);

                $entityManager = $userRepository->getEntityManager();
                $entityManager->update($user);
                $entityManager->flush();

                $resetUrl = $request->getSchemeAndHttpHost() . $this->router->generateUri('handle_reset_password');
                $resetUrl .= "?reset_token=$token";

                $emailContent = $this->renderView('mails/reset_password.html.twig', ['url' => $resetUrl], false);

                $email = (new Email())
                    ->from($_ENV['MAILER_SENDER'])
                    ->to($user->getEmail())
                    ->priority(Email::PRIORITY_NORMAL)
                    ->subject("Réinitialisation de votre mot de passe")
                    ->text(strip_tags(Html2Text::convert($emailContent)))
                    ->html(nl2br($emailContent));

                try {
                    $mailer->send($email);

                    return $this->redirect($this->router->generateUri('login'));
                } catch (Throwable $exception) {
                    $session->getFlashBag()->add('danger', "Une erreur inconnue est survenue");
                }
            }
        }

        return $this->render('pages/front/reset_password.html.twig', ['errors' => $form->getErrors()]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws RouteNotFoundException
     * @throws Exception
     */
    public function handleResetPassword(
        FormHandler $formHandler,
        Request $request,
        UserRepository $userRepository,
    ): Response {
        $this->denyAccessUnlessNotLoggedIn();

        $token = (string)$request->query->get('reset_token');

        /** @var User|false $user */
        $user = $userRepository->findOneBy(['reset_token' => $token]);

        if (! $token || ! $user) {
            throw new RouteNotFoundException();
        }

        $handleResetPassword = new HandleResetPassword();
        $form                = $formHandler->loadFromRequest($request, $handleResetPassword);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = User::encodePassword($form->get('plainPassword'));

            $user->setPassword($newPassword);
            $user->setResetToken();

            $entityManager = $userRepository->getEntityManager();
            $entityManager->update($user);
            $entityManager->flush();

            /** @var Session $session */
            $session = $request->getSession();
            $session->getFlashBag()->add(
                'success',
                "Mot de passe modifié avec succès, vous pouvez désormais vous connecter"
            );

            return $this->redirect($this->router->generateUri('login'));
        }

        return $this->render('pages/front/handle_reset_password.html.twig', ['errors' => $form->getErrors()]);
    }

    /**
     * @throws RouteNotFoundException
     */
    public function logout(Authenticator $auth, Request $request): RedirectResponse
    {
        $auth->logout();

        /** @var Session $session */
        $session = $request->getSession();
        $session->getFlashBag()->add('success', 'Vous êtes désormais déconnecté');

        return $this->redirect($this->router->generateUri('home'));
    }
}
