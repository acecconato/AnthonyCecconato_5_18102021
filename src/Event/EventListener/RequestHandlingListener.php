<?php

declare(strict_types=1);

namespace Blog\Event\EventListener;

use Blog\Repository\UserRepository;
use Blog\Security\Authenticator;
use Symfony\Component\HttpFoundation\Request;

class RequestHandlingListener implements EventListenerInterface
{
    public function __construct(
        private readonly Authenticator $authenticator,
        private readonly UserRepository $userRepository
    ) {
    }

    public function execute(mixed $event): void
    {
        $object = $event->getObject();

        if (!$object instanceof Request) {
            return;
        }

        $request = $object;

        $username = $request->cookies->get('username');
        $rememberToken = $request->cookies->get('remember_token');

        /**
         * Login automatically if is remembered
        */
        if (!$this->authenticator->isValid() && $username && $rememberToken) {
            $user = $this->userRepository->getUserByUsernameOrEmail($username);

            if ($user && $user->getRememberToken() === $rememberToken) {
                $this->authenticator->authenticate($user);
            }
        }
    }
}
