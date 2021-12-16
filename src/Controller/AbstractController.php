<?php

declare(strict_types=1);

namespace Blog\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    /**
     * @param string $rawResponse
     * @return Response
     */
    public function raw(string $rawResponse): Response
    {
        return new Response($rawResponse);
    }
}
