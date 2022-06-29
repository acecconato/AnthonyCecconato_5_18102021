<?php

declare(strict_types=1);

namespace Blog\Event\EventListener;

use Blog\DependencyInjection\ContainerInterface;
use Blog\Event\PreRequestHandlingEvent;
use Blog\Repository\UserRepository;
use Blog\Security\Authenticator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestHandlingListener
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(PreRequestHandlingEvent $event, ContainerInterface $container): void
    {
        $object = $event->getObject();

        if (!$object instanceof Request) {
            return;
        }

        $request = $object;

        /** @var Authenticator $authenticator */
        $authenticator = $container->get(Authenticator::class);

        $username = $request->cookies->get('username');
        $rememberToken = $request->cookies->get('remember_token');

        /** Login automatically if is remembered */
        if (!$authenticator->isValid() && $username && $rememberToken) {
            /** @var UserRepository $userRepository */
            $userRepository = $container->get(UserRepository::class);
            $user = $userRepository->getUserByUsernameOrEmail($username);

            if ($user && $user->getRememberToken() === $rememberToken) {
                $authenticator->authenticate($user);
            }
        }
    }
}