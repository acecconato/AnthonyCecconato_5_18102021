<?php

declare(strict_types=1);

namespace Blog\ORM\Hydration;

use Blog\ORM\Mapping\DataMapper;

abstract class AbstractHydrator
{
    public function __construct(
        protected DataMapper $mapper
    ) {
    }
}