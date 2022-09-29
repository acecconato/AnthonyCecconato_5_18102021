<?php

declare(strict_types=1);

namespace Blog\ORM\Mapping\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Entity
{
    public function __construct(
        public string $repositoryClass
    ) {
    }
}
