<?php

namespace Tests;

use Blog\DependencyInjection\Container;
use Blog\Templating\Templating;
use Blog\Templating\TemplatingInterface;
use PHPUnit\Framework\TestCase;

class TemplatingTest extends TestCase
{
    public function testTemplating()
    {
        $_ENV['APP_ENV'] = 'tests';
        $_ENV['TWIG_DEBUG'] = 'false';

        $container = new Container();
        $container->addAlias(TemplatingInterface::class, Templating::class);
        $container->addParameter('cacheDir', sprintf('%s/../var/cache/%s', __DIR__, 'tests'))
                  ->addParameter('templatesDirs', dirname(__DIR__) . '/templates');

        $this->assertInstanceOf(Templating::class, $container->get(Templating::class));
    }
}