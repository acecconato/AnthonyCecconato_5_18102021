<?php

declare(strict_types=1);

namespace Tests;

use Blog\Database\Adapter\AdapterInterface;
use Blog\Database\Adapter\MySQLAdapter;
use Blog\DependencyInjection\Container;
use Blog\ORM\Mapping\DataMapper;
use Blog\ORM\Mapping\MapperInterface;
use Blog\Validator\Constraint\StrongPassword;
use Blog\Validator\Validator;
use PHPUnit\Framework\TestCase;

class PasswordStrengthTest extends TestCase
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

    public function testAtLeastOneLowerCase()
    {
        $container = $this->loadContainer();

        /** @var Validator $validator */
        $validator = $container->get(Validator::class);
        $constraint = new StrongPassword();

        $this->assertTrue($validator->validate('ATLEaSTONELOWERCASE00!', $constraint));
        $this->assertFalse($validator->validate('ATLEASTONELOWERCASE00!!', $constraint));
    }

    public function testAtLeastOneUpperCase()
    {
        $container = $this->loadContainer();

        /** @var Validator $validator */
        $validator = $container->get(Validator::class);
        $constraint = new StrongPassword();

        $this->assertTrue($validator->validate('atleastoneUppercase00!!', $constraint));
        $this->assertFalse($validator->validate('atleastoneuppercase00!!', $constraint));
    }

    public function testAtLeastOneDigit()
    {
        $container = $this->loadContainer();

        /** @var Validator $validator */
        $validator = $container->get(Validator::class);
        $constraint = new StrongPassword();

        $this->assertTrue($validator->validate('Atleast1digit!!', $constraint));
        $this->assertFalse($validator->validate('Atleastonedigit!!', $constraint));
    }
}
