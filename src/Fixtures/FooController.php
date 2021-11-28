<?php

declare(strict_types=1);

namespace Blog\Fixtures;

/**
 * Class FooController
 * @package Blog\Fixtures
 */
class FooController
{
    public function index(string $bar)
    {
        return $bar;
    }
}
