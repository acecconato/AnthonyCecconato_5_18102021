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

        // TODO:: These parameters should be into the .env file
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

        $em = $postRepo->getEntityManager();

        $post = new Post();
        $post
            ->setUserId((string) Uuid::uuid4())
            ->setContent('Short content')
            ->setTitle('Simple Title')
            ->setSlug('short-content');

        $em->add($post);
        $em->flush();
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