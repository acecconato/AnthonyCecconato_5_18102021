<?php

declare(strict_types=1);

namespace Blog\Entity;

use Blog\Attribute\Column;
use Blog\Attribute\Entity;
use Blog\Attribute\Table;
use Blog\Repository\UserRepository;

#[Entity(repositoryClass: UserRepository::class)]
#[Table(tableName: 'user')]
class User
{
    #[Column(name: 'ousername')]
    private string $username;

    #[Column(name: 'email', unique: true)]
    private string $email;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }
}