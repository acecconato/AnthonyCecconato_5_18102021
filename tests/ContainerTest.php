<?php

declare(strict_types=1);

namespace Tests;

use Blog\Container\Container;
use Blog\Container\Exceptions\NotFoundException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testContainer()
    {
        $services = [
            'my.service'   => fn($test) => 'Hello',
            'my.parameter' => 42,
        ];

        $container = new Container($services);

        $this->assertEquals('Hello', $container->get('my.service'));
        $this->assertEquals(42, $container->get('my.parameter'));

        $this->expectException(NotFoundException::class);
        $container->get('unknown');
    }
}
