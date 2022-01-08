<?php

declare(strict_types=1);

namespace Blog\Controller;

use Blog\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    protected ContainerInterface $container;

    /**
     * @param  string $rawResponse
     * @return Response
     */
    public function raw(string $rawResponse): Response
    {
        return new Response($rawResponse);
    }

    /**
     * @param ContainerInterface $container
     * @return void
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    /**
     * @param string $to
     * @param int $status
     * @param array $headers
     * @return RedirectResponse
     */
    public function redirect(string $to, int $status = 302, array $headers = []): RedirectResponse
    {
        return new RedirectResponse($to, $status, $headers);
    }
}
