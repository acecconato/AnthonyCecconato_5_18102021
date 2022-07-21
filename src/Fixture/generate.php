<?php

use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\Kernel;
use Blog\ORM\EntityManager;
use Faker\Factory;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

if (PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) {
    die('Please launch this script with the proper CLI command');
}

require __DIR__ . '/../../vendor/autoload.php';

$dotenv = new Dotenv();

if (!file_exists(__DIR__ . '/../../.env')) {
    throw new FileNotFoundException('.env file not found');
}

$dotenv->loadEnv(__DIR__ . '/../../.env');

$kernel = new Kernel('dev');

$container = $kernel->getContainer();

/** @var EntityManager $em */
$em = $container->get(EntityManager::class);

$faker = Factory::create();

for ($i = 0; $i < 5; $i++) {
    $user = new User();
    $user
        ->setEmail($faker->email)
        ->setUsername($faker->userName)
        ->setPassword($faker->password);

    $em->add($user);
}

$demoUser = new User();
$demoUser
    ->setUsername('demo')
    ->setEmail('demo@demo.fr')
    ->setPassword(User::encodePassword('demo_password'));

$em->add($demoUser);

for ($i = 0; $i < 20; $i++) {
    $post = new Post();
    $post
        ->setTitle($faker->sentence)
        ->setContent($faker->randomHtml())
        ->setSlug(null)
        ->setUserId($demoUser->getId())
        ->setCreatedAt((new DateTime())->modify("+$i minutes"));

    $em->add($post);
}

$em->flush();

die("OK");
