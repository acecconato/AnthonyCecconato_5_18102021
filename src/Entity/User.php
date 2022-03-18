<?php

declare(strict_types=1);

namespace Blog\Entity;

use Blog\ORM\Mapping\Attribute\Column;
use Blog\ORM\Mapping\Attribute\Entity;
use Blog\ORM\Mapping\Attribute\Enum\Type;
use Blog\ORM\Mapping\Attribute\Id;
use Blog\ORM\Mapping\Attribute\Table;
use Blog\Repository\UserRepository;

#[Entity(repositoryClass: UserRepository::class)]
#[Table(tableName: 'user')]
class User extends AbstractEntity
{
    #[Column(name: 'username', type: Type::STRING, nullable: false, unique: true)]
    private string $username;

    #[Column(name: 'email', type: Type::STRING, nullable: false, unique: true)]
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