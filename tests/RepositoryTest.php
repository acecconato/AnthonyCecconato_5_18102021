<?php

namespace Tests;

use Blog\Database\Adapter\AdapterInterface;
use Blog\Database\Adapter\MySQLAdapter;
use Blog\Database\DataMapper;
use Blog\Database\MapperInterface;
use Blog\DependencyInjection\Container;
use Blog\Entity\EntityManager;
use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    public function test()
    {
        $container = new Container();
        $container
            ->addAlias(AdapterInterface::class, MySQLAdapter::class)
            ->addAlias(MapperInterface::class, DataMapper::class);

        // TODO:: These parameters should be into the .env file
        $container
            ->addParameter('host', 'localhost')
            ->addParameter('dbName', 'phpsf05')
            ->addParameter('dbUser', 'root')
            ->addParameter('dbPassword', 'root');

        // A controller call the required repository (User here)
        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);

        // We retrieve the EntityManager from the proper repository
        $em = $userRepository->getEntityManager();
        $this->assertInstanceOf(EntityManager::class, $em);

        // We can create our entities
        $john = new User();
        $john
            ->setEmail('johndoe@gmail.com')
            ->setUsername('John Doe');

        $sarah = new User();
        $sarah
            ->setEmail('sarahdoe@gmail.com')
            ->setUsername('Sarah Doe');

        // Then persist our entities
        $em
            ->add($john)
            ->add($sarah);

        $myPost = new Post();
        $myPost->setTitle('A simple title')->setContent('A short content');

        $em->add($myPost);

        // Finally, save them into the database through the flush method
        $em->flush();
    }
}