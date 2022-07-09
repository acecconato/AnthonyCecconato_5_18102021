<?php

namespace Tests;

use Blog\Database\Adapter\AdapterInterface;
use Blog\Database\Adapter\MySQLAdapter;
use Blog\DependencyInjection\Container;
use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\ORM\EntityManager;
use Blog\ORM\Mapping\DataMapper;
use Blog\ORM\Mapping\MapperInterface;
use Blog\Repository\PostRepository;
use Blog\Repository\UserRepository;
use Blog\Validator\Validator;
use Blog\Validator\ValidatorInterface;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class RepositoryTest extends TestCase
{
    public function loadContainer(): Container
    {
        $container = new Container();
        $container
            ->addAlias(AdapterInterface::class, MySQLAdapter::class)
            ->addAlias(MapperInterface::class, DataMapper::class);

        return $container
            ->addParameter('host', 'localhost')
            ->addParameter('dbName', 'anthonyc5')
            ->addParameter('dbUser', 'root')
            ->addParameter('dbPassword', 'root');
    }

    public function testMain(): void
    {
        $container = $this->loadContainer();

        // A controller call the required repository (User here)
        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);

        // We retrieve the EntityManager from the proper repository
        $em = $userRepository->getEntityManager();
        $this->assertInstanceOf(EntityManager::class, $em);

        $container->get(Validator::class);

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

        $this->assertEquals($john->getId(), $myPost->getUserId());

        $em->delete($sarah);
        $em->flush();

        $sarah = $userRepository->find($sarah->getId());

        $this->assertFalse($sarah);

        $userRepository->delete([$john->getId()]);
        $john = $userRepository->find($john->getId());

        $this->assertFalse($john);

        $myPost = $em->find(Post::class, $myPost->getId());

        $this->assertIsObject($myPost);

        /** @phpstan-ignore-next-lines */
        $this->assertNull($myPost->getUserId());

        /** @phpstan-ignore-next-lines */
        $em->deleteById(Post::class, [$myPost->getId()]);

        $em->flush();

        /** @phpstan-ignore-next-lines */
        $this->assertFalse($em->findOneBy(Post::class, ['id' => $myPost->getId()]));
    }

    public function testPostRepository(): void
    {
        $container = $this->loadContainer();

        /** @var PostRepository $postRepo */
        $postRepo = $container->get(PostRepository::class);
        /** @var UserRepository $userRepo */
        $userRepo = $container->get(UserRepository::class);
        /** @var EntityManager $em */
        $em = $container->get(EntityManager::class);
        /** @var Validator $validator */
        $validator = $container->get(ValidatorInterface::class);

        $faker = Factory::create();

        $user = new User();
        $user
            ->setEmail('notanemail')
            ->setUsername('a')
            ->setPassword(User::encodePassword($faker->password));

        if (!$validator->validateObject($user)) {
            $this->assertCount(2, $validator->getErrors());
        }

        $user
            ->setEmail($faker->email)
            ->setUsername($faker->userName);

        $this->assertTrue($validator->validateObject($user));

        $em->add($user);
        $em->flush();
        $this->assertInstanceOf(User::class, $userRepo->find($user->getId()));

        $post = new Post();
        $post
            ->setUserId($user->getId())
            ->setContent($faker->realText)
            ->setTitle($faker->sentence);
        $em->add($post);
        $em->flush();
//
//        $this->assertTrue(Uuid::isValid($post->getId()));
//
//        /** @var Post $newPost */
//        $newPost = $postRepo->find($post->getId());
//        $newPost->setTitle('My new TITLE!!' . uniqid());
//        $em->update($newPost);
//        $em->flush();
//
//        $this->assertNotNull($newPost->getUpdatedAt());

        $em->delete($user);
        $em->flush();
        $this->assertFalse($userRepo->find($user->getId()));
    }

    public function testUpdates()
    {
        $container = $this->loadContainer();

        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);

        /** @var EntityManager $em */
        $em = $userRepository->getEntityManager();

        $billy = new User();
        $billy
            ->setUsername('Billy The Kid')
            ->setEmail('billy@thekid.com');

        $em->add($billy);
        $em->flush();

        $this->assertInstanceOf(User::class, $userRepository->find($billy->getId()));

        $billy->setUsername("Billy the updated");

        $em->update($billy);
        $em->flush();

        $this->assertEquals('Billy the updated', $userRepository->find($billy->getId())?->getUsername());

        $em->delete($billy);
        $em->flush();

        $this->assertFalse($userRepository->find($billy->getId()));
    }
}