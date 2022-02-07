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
    /** @var string */
    #[Column(name: 'username')]
    private string $username;

    /** @var string */
    #[Column(name: 'email', unique: true)]
    private string $email;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }
}