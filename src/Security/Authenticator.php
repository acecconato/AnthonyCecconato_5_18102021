<?php

declare(strict_types=1);

namespace Blog\Security;

use Blog\Entity\User;
use Blog\ORM\EntityManager;
use Blog\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Request;

class Authenticator
{
    public function __construct(
        private readonly Request $request,
        private readonly EntityManager $entityManager
    ) {
    }

    public function authenticate(User $user, bool $remember = false): void
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

        if ($remember) {
            $now = new DateTimeImmutable();
            $expires = $now->modify('+3 month')->getTimestamp();

            $rememberMeToken = md5(uniqid());

            setcookie('username', $user->getUsername(), $expires);
            setcookie('remember_token', $rememberMeToken, $expires);

            $user->setRememberToken($rememberMeToken);
            $this->entityManager->update($user);
            $this->entityManager->flush();
        }
    }

    public function isValid(): bool
    {
        $session = $this->request->getSession();

        if ($session->get('clientIp') !== $this->getAnonymizedClientIp()) {
            return false;
        }

        if ($session->get('userAgent') !== $this->getUserAgent()) {
            return false;
        }

        if (time() < $session->get('lastAccess') + 3600) {
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
        dump($this->request->getSession()->getId());
        $is = $this->request->getSession()->migrate();
        dump($this->request->getSession()->getId());
        return $is;
    }

    public function logout(): void
    {
        $this->request->getSession()->clear();

        $now = (new DateTimeImmutable())->getTimestamp();
        setcookie('username', '', $now);
        setcookie('remember_token', '', $now);
    }
}
