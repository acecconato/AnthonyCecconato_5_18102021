<?php

declare(strict_types=1);

namespace Blog\Attribute;

use Attribute;
use Blog\Attribute\Enum\Type;

#[Attribute]
final class Column
{
    public function __construct(
        public string $name,
        public Type $type = Type::STRING,
        public bool $nullable = false,
        public bool $unique = false
    ) {
    }
}