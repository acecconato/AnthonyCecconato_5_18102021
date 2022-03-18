<?php

namespace Tests;

use Blog\Database\Adapter\AdapterInterface;
use Blog\Database\Adapter\MySQLAdapter;
use Blog\DependencyInjection\Container;
use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\ORM\EntityManager;
use Blog\ORM\Hydration\ObjectHydrator;
use Blog\ORM\Mapping\DataMapper;
use Blog\ORM\Mapping\MapperInterface;
use Blog\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Rfc4122\UuidV4;

class RepositoryTest extends TestCase
{
    public function loadContainer(): Container
    {
        $container = new Container();
        $container
            ->addAlias(AdapterInterface::class, MySQLAdapter::class)
            ->addAlias(MapperInterface::class, DataMapper::class);

        // TODO:: These parameters should be into the .env file
        return $container
            ->addParameter('host', 'localhost')
            ->addParameter('dbName', 'phpsf05')
            ->addParameter('dbUser', 'root')
            ->addParameter('dbPassword', 'root');
    }

    public function testEntityManagerAdd(): void
    {
        $container = $this->loadContainer();

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

        $em
            ->add($john)
            ->add($sarah);

        $myPost = new Post();
        $myPost
            ->setTitle('A simple title')
            ->setContent('A short content')
            ->setUserId($john->getId());

        $em->add($myPost);

        $em->flush();

        $this->assertTrue(UuidV4::isValid($john->getId()));
    }

    public function testUserRepository(): void
    {
        $container = $this->loadContainer();

        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);

        $users = $userRepository->findAll();

        $this->assertGreaterThan(0, count($users));
        $this->assertContainsOnlyInstancesOf(User::class, $users);
    }

    public function testFind(): void
    {
        $container = $this->loadContainer();

        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);

        $em = $userRepository->getEntityManager();

        dd($userRepository->findOneBy(['id' => '303e9922-ae1b-4279-98d3-bca7452a2c4e', 'username' => 'John Doe']));
    }
}