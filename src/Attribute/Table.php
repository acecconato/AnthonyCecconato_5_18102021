<?php

declare(strict_types=1);

namespace Blog\Attribute;

use Attribute;

#[Attribute]
final class Table
{
    public function __construct(
        private string $tableName
    ) {
    }
}