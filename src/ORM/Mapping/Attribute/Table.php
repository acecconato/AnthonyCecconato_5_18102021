<?php

declare(strict_types=1);

namespace Blog\ORM\Mapping\Attribute;

use Attribute;

#[Attribute]
final class Table
{
    public function __construct(
        public string $tableName
    ) {
    }
}
