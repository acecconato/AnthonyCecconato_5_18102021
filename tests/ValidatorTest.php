<?php

namespace Tests;

use Blog\Database\Adapter\AdapterInterface;
use Blog\Database\Adapter\MySQLAdapter;
use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\Hydration\ObjectHydrator;
use Blog\Kernel;
use Blog\ORM\EntityManager;
use Blog\ORM\Mapping\DataMapper;
use Blog\ORM\Mapping\MapperInterface;
use Blog\Validator\Constraint\Email;
use Blog\Validator\Constraint\MaxLength;
use Blog\Validator\Constraint\MinLength;
use Blog\Validator\Constraint\NotBlank;
use Blog\Validator\Constraint\NotNull;
use Blog\Validator\Constraint\Slug;
use Blog\Validator\Constraint\Unique;
use Blog\Validator\Validator;
use Exception;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ValidatorTest extends TestCase
{
    private Validator $validator;
    private EntityManager $em;
    private ObjectHydrator $hydrator;

    protected function setUp(): void
    {
        $dotenv = new Dotenv();

        if (!file_exists(dirname(__DIR__) . '/.env')) {
            throw new FileNotFoundException('.env file not found');
        }

        $dotenv->loadEnv(dirname(__DIR__) . '/.env');

        $kernel = new Kernel('test');
        $container = $kernel->getContainer();

        $container
            ->addAlias(AdapterInterface::class, MySQLAdapter::class)
            ->addAlias(MapperInterface::class, DataMapper::class);

        $container
            ->addParameter('host', 'localhost')
            ->addParameter('dbName', 'anthony5')
            ->addParameter('dbUser', 'root')
            ->addParameter('dbPassword', '');

        $this->validator = $container->get(Validator::class);
        $this->em = $container->get(EntityManager::class);
        $this->hydrator = $container->get(ObjectHydrator::class);
    }

    public function testEmail()
    {
        $constraint = Email::getInstance();
        $this->assertTrue($this->validator->validate('valid@email.fr', $constraint));
        $this->assertFalse($this->validator->validate('not-an-email', $constraint));
    }

    public function testMaxLength()
    {
        /** @var MaxLength $constraint */
        $constraint = MaxLength::getInstance();
        $constraint->maxLength = 5;

        $this->assertTrue($this->validator->validate('okay', $constraint));
        $this->assertFalse($this->validator->validate('too much characters', $constraint));
    }

    public function testMinLength()
    {
        /** @var MinLength $constraint */
        $constraint = MinLength::getInstance();
        $constraint->minLength = 5;

        $this->assertTrue($this->validator->validate('okay!', $constraint));
        $this->assertFalse($this->validator->validate('no', $constraint));
    }

    public function testNotBlank()
    {
        $constraint = NotBlank::getInstance();

        $this->assertTrue($this->validator->validate('okay', $constraint));
        $this->assertFalse($this->validator->validate('', $constraint));
    }

    public function testNotNull()
    {
        $constraint = NotNull::getInstance();

        $this->assertTrue($this->validator->validate(false, $constraint));
        $this->assertFalse($this->validator->validate(null, $constraint));
    }

    public function testSlug()
    {
        $constraint = Slug::getInstance();

        $this->assertTrue($this->validator->validate('i-am-a-slug', $constraint));
        $this->assertFalse($this->validator->validate("I'm not a slug!", $constraint));
    }

    public function testUuid()
    {
        $constraint = \Blog\Validator\Constraint\Uuid::getInstance();

        $this->assertTrue($this->validator->validate((string)Uuid::uuid4(), $constraint));
        $this->assertFalse($this->validator->validate("not an uuid", $constraint));
    }

    public function testUnique()
    {
        $constraint = new Unique(User::class, 'username');
        $user = new User();
        $user
            ->setUsername('johndoe')
            ->setEmail('demo@demo.fr')
            ->setPassword(User::encodePassword('plain_password'));

        $this->em->add($user);
        $this->em->flush();

        $this->assertTrue($this->validator->validate('shouldBeUnique', $constraint));
        $this->assertFalse($this->validator->validate('johndoe', $constraint));

        $this->assertGreaterThan(0, count($this->validator->getErrors()));

        $this->em->delete($user);
        $this->em->flush();
    }

    public function testValidateObject()
    {
        $post = new Post();
        $post
            ->setTitle('title')
            ->setContent('test');

        $newPost = $this->hydrator->hydrateSingle([
            'title' => 'My awesome title',
            'content' => 'Content of the post'
        ], new Post());

        $this->assertTrue($this->validator->validateObject($newPost));

        $this->expectException(Exception::class);
        $this->validator->validateObject($post);
    }
}