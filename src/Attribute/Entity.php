<?php

declare(strict_types=1);

namespace Blog\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Entity
{
    public function __construct(
        protected string $repositoryClass
    ) {
    }
}