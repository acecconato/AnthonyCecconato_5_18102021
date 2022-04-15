<?php

declare(strict_types=1);

use Blog\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

require_once '../vendor/autoload.php';

$dotenv = new Dotenv();

if (!file_exists(dirname(__DIR__) . '/.env')) {
    throw new FileNotFoundException('.env file not found');
}

$dotenv->loadEnv(dirname(__DIR__) . '/.env');

if ($_ENV['APP_ENV'] === 'development') {
    ini_set('display_errors', 'true');
}

try {
    $kernel = new Kernel($_ENV['APP_ENV']);
    $kernel->run(Request::createFromGlobals(), new Session());
} catch (Throwable $e) {
    dd($e->getMessage());
}
