<?php

declare(strict_types=1);

namespace Blog\Attribute;

use Attribute;

#[Attribute]
final class Column
{
    public function __construct(
        private string $name,
        private string $type = 'string',
        private bool $nullable = false,
        private bool $unique = false
    ) {
    }
}