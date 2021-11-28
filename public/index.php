<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

require_once 'vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->loadEnv(dirname(__DIR__).'/.env');

$request = Request::createFromGlobals();
//$router = new Router();
