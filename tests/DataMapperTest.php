<?php

declare(strict_types=1);

namespace Tests;

use Blog\Database\DataMapper;
use Blog\Entity\User;
use PHPUnit\Framework\TestCase;

class DataMapperTest extends TestCase
{
    public const DATASET = [
        'id' => '147246bc-4682-421d-ae51-3f3bdb78f3ff',
        'username' => 'john',
        'email' => 'johndoe@email.com'
    ];

    public function test()
    {
        $mapper = new DataMapper();
        $mapper->mapRowToEntity(DATASET, User::class);
    }
}