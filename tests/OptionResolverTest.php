<?php

declare(strict_types=1);

namespace Tests;

use Blog\Resolver\Option;
use Blog\Resolver\OptionsResolver;
use PHPUnit\Framework\TestCase;

class OptionResolverTest extends TestCase
{
    public function test()
    {
        $resolver = new OptionsResolver([
            new Option('action'),
            (new Option('method'))->setDefaultValue('POST')
        ]);

        $options = $resolver->resolve([
            'action' => true
        ]);

        $this->assertSame($options, ['action' => true, 'method' => 'POST']);

        $this->expectException(\InvalidArgumentException::class);

        $options = $resolver->resolve([
            'method' => 'GET'
            // missing 'action' option
        ]);
    }

    public function testValid()
    {
        $resolver = new OptionsResolver([
            (new Option('action'))->setValidator(fn($value) => filter_var($value, FILTER_VALIDATE_URL)),
            (new Option('method'))->setDefaultValue('POST')
        ]);

        $options = $resolver->resolve([
            'action' => 'https://google.com' // must be an url, ok here
        ]);

        $this->assertSame($options, ['action' => 'https://google.com', 'method' => 'POST']);

        $this->expectException(\InvalidArgumentException::class);

        $fOptions = $resolver->resolve([
           'action' => 'not-an-url'
        ]);
    }
}