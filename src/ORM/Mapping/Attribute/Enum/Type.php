<?php

declare(strict_types=1);

namespace Blog\ORM\Mapping\Attribute\Enum;

enum Type
{
    case HTML;
    case STRING;
    case INT;
    case UUID;
    case DATE;
}