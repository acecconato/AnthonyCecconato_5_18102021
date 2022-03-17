<?php

declare(strict_types=1);

namespace Blog\ORM\Hydration;

use Blog\Database\DataMapper;

abstract class AbstractHydrator
{
    public function __construct(
        protected DataMapper $mapper
    ) {
    }
}