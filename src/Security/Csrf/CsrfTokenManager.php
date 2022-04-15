<?php

declare(strict_types=1);

namespace Blog\Security\Csrf;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CsrfTokenManager
{
//    private SessionInterface $session;
//
//    public function __construct(
//        private Request $request
//    ) {
//        $this->session = $this->request->getSession();
//    }

//    public function setToken(string $id): void
//    {
//        $this->session->set($id, uniqid());
//        $this->write();
//    }

    public function getToken(): mixed
    {
        dd("hey");
    }

//    public function hasToken(string $id): bool
//    {
//        return $this->session->has($id);
//    }
//
//    public function write(): void
//    {
//        $this->request->setSession($this->session);
//    }
}