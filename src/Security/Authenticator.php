<?php

declare(strict_types=1);

namespace Blog\Security;

use Blog\Entity\User;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Request;

class Authenticator
{
    public function __construct(
        private readonly Request $request
    ) {
    }

    public function authenticate(User $user): void
    {
        $session = $this->request->getSession();

        $clientIp = $this->getAnonymizedClientIp();
        $userAgent = $this->getUserAgent();

        $session->set('isLoggedIn', true);
        $session->set('userAgent', $userAgent);
        $session->set('clientIp', $clientIp);
        $session->set('user', [
            'username' => $user->getUsername(),
            'email' => $user->getEmail()
        ]);
    }

    public function isValid(): bool
    {
        $session = $this->request->getSession();

        if ($session->get('clientIp') !== $this->getAnonymizedClientIp()) {
            $this->logout();
            return false;
        }

        if ($session->get('userAgent') !== $this->getUserAgent()) {
            $this->logout();
            return false;
        }

        if (time() < $session->get('lastAccess') + 3600) {
            $this->logout();
            return false;
        }

        $session->set('lastAcces', time());
        return true;
    }

    public function getAnonymizedClientIp(): string
    {
        return IpUtils::anonymize($_SERVER['REMOTE_ADDR']);
    }

    public function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function refresh(): bool
    {
        return $this->request->getSession()->migrate();
    }

    public function logout(): bool
    {
        return $this->request->getSession()->invalidate();
    }
}
