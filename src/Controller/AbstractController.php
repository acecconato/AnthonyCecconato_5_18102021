<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\Controller\Exception\UnauthorizedException;
use Blog\Router\Router;
use Blog\Security\Authenticator;
use Blog\Templating\TemplatingInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    public function __construct(
        protected TemplatingInterface $templating,
        protected Router $router,
        protected Authenticator $authenticator
    ) {
    }

    /**
     * @param string $rawResponse
     *
     * @return Response
     */
    public function raw(string $rawResponse): Response
    {
        return new Response($rawResponse);
    }

    /**
     * @param string $to
     * @param int $status
     * @param array<mixed> $headers
     *
     * @return RedirectResponse
     */
    public function redirect(string $to, int $status = 302, array $headers = []): RedirectResponse
    {
        return new RedirectResponse($to, $status, $headers);
    }

    /**
     * @param string $view
     * @param array<mixed> $context
     *
     * @return Response
     */
    public function render(string $view, array $context = []): Response
    {
        return new Response($this->templating->render($view, $context));
    }

    /**
     * @param array<mixed> $context
     */
    public function renderView(string $view, array $context, bool $mainRequest = true): string
    {
        return $this->templating->render($view, $context, $mainRequest);
    }

    /**
     * @throws UnauthorizedException
     */
    public function denyAccessUnlessLoggedIn(): void
    {
        if (!$this->authenticator->isLoggedIn()) {
            throw new UnauthorizedException();
        }
    }

    /**
     * @throws UnauthorizedException
     */
    public function denyAccessUnlessIsAdmin(): void
    {
        if (!$this->authenticator->isLoggedIn() || !$this->authenticator->isAdmin()) {
            throw new UnauthorizedException();
        }
    }

    /**
     * @throws UnauthorizedException
     */
    public function denyAccessUnlessNotLoggedIn(): void
    {
        if ($this->authenticator->isLoggedIn()) {
            throw new UnauthorizedException();
        }
    }
}
