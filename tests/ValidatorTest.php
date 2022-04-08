<?php

namespace Tests;

use Blog\Controller\HomeController;
use Blog\DependencyInjection\Container;
use Blog\Entity\User;
use Blog\Kernel;
use Blog\Templating\Templating;
use Blog\Templating\TemplatingInterface;
use Blog\Validator\Validator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ValidatorTest extends TestCase
{
    public function testMain(): void
    {
        $dotenv = new Dotenv();

        if (!file_exists(dirname(__DIR__) . '/.env')) {
            throw new FileNotFoundException('.env file not found');
        }

        $dotenv->loadEnv(dirname(__DIR__) . '/.env');

        $kernel = new Kernel('test');
        $kernel->configureContainer();

        $container = $kernel->getContainer();

        /** @var Validator $validator */
        $validator = $container->get(Validator::class);

        $myUser = new User();
        $myUser->setUsername('John')->setEmail('notvalid')->setId('not-an-uuid');

        $validation = $validator->validateObject($myUser);

        if (!$validation) {
            dd($validator->getErrors());
        }
    }
}