<?php

declare(strict_types=1);

namespace Blog\Twig;

use Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CsrfTokenExtension extends AbstractExtension
{
    public function __construct(
        private SessionInterface $session
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('csrf_token', [$this, 'generateCsrfToken'])
        ];
    }

    /**
     * @throws Exception
     */
    public function generateCsrfToken(): string
    {
        $token = md5(uniqid('csrf_'));

        $this->session->set('csrf_token', $token);

        return $token;
    }
}