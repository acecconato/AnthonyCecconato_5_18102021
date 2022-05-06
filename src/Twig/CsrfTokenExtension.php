<?php

declare(strict_types=1);

namespace Blog\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CsrfTokenExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('csrf_token', [$this, 'generateCsrfToken'])
        ];
    }

    public function generateCsrfToken(): string
    {
        $token = md5(uniqid('csrf_'));

        $request = Request::createFromGlobals();
        $request->setSession(new Session());
        $request->getSession()->set('csrf_token', $token);

        return $token;
    }
}