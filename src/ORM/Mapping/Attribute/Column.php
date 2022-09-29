<?php

declare(strict_types=1);

namespace Blog\ORM\Mapping\Attribute;

use Attribute;
use Blog\ORM\Mapping\Attribute\Enum\Type;

#[Attribute]
final class Column
{
    public function __construct(
        public string $name,
        public Type $type = Type::STRING,
    ) {
    }
}
