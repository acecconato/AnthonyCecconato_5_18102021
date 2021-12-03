<?php

declare(strict_types=1);

use Blog\Router\Router;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;

require_once 'vendor/autoload.php';

$dotenv = new Dotenv();

if (!file_exists(dirname(__DIR__).'/.env')) {
    throw new FileNotFoundException('.env file not found');
}

$dotenv->loadEnv(dirname(__DIR__).'/.env');

$request = Request::createFromGlobals();

var_dump($request->getUri());

$router = new Router($request);

$routes = require_once '../config/routes.php';
foreach ($routes as $route) {
    $router->add($route);
}

