<?php

namespace Tests;

use Blog\Database\Adapter\AdapterInterface;
use Blog\Database\Adapter\MySQLAdapter;
use Blog\Kernel;
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
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ValidatorTest extends TestCase
{
    private Validator $validator;

    protected function setUp(): void
    {
        $dotenv = new Dotenv();

        if (!file_exists(dirname(__DIR__) . '/.env')) {
            throw new FileNotFoundException('.env file not found');
        }

        $dotenv->loadEnv(dirname(__DIR__) . '/.env');

        $kernel = new Kernel('test');
        $kernel->configureContainer();

        $container = $kernel->getContainer();

        $container
            ->addAlias(AdapterInterface::class, MySQLAdapter::class)
            ->addAlias(MapperInterface::class, DataMapper::class);

        $container
            ->addParameter('host', 'localhost')
            ->addParameter('dbName', 'anthonyc5')
            ->addParameter('dbUser', 'root')
            ->addParameter('dbPassword', 'root');

        $this->validator = $container->get(Validator::class);
    }

    // TODO Q/A Quand je lance les tests manuellement, un a un c'est ok; quand je les lance tous, ça plante

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
        /** @var Unique $constraint */
        $constraint = Unique::getInstance();
        $constraint->tableName = 'user';
        $constraint->column = 'username';

        $this->validator->validate('shouldBeUnique', $constraint);
    }
}