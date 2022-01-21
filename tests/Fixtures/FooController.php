<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Blog\Controller\AbstractController;
use Blog\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FooController
 * @package Tests\Fixtures
 */
class FooController extends AbstractController
{
    public function index()
    {
        return $this->raw('index');
    }
}
