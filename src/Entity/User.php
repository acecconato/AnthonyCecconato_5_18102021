<?php

declare(strict_types=1);

namespace Blog\Entity;

use Blog\ORM\Mapping\Attribute\Column;
use Blog\ORM\Mapping\Attribute\Entity;
use Blog\ORM\Mapping\Attribute\Enum\Type;
use Blog\ORM\Mapping\Attribute\Id;
use Blog\ORM\Mapping\Attribute\Table;
use Blog\Repository\UserRepository;
use Blog\Validator\Constraint as Assert;
use Ramsey\Uuid\Uuid;

#[Entity(repositoryClass: UserRepository::class)]
#[Table(tableName: 'user')]
class User
{
    #[Id()]
    protected string $id;

    #[Column(name: 'username', type: Type::STRING, nullable: false, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\MaxLength(
        message: "Le nom d'utilisateur '%s' ne peut excéder %d caractères. Il en possède actuellement %d",
        maxLen: 20)
    ]
    private string $username;

    #[Column(name: 'email', type: Type::STRING, nullable: false, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\Email()]
    private string $email;

    public function __construct()
    {
        if (!isset($this->id)) {
            $this->id = (string)Uuid::uuid4();
        }
    }

    public function getId(): string|false
    {
        return $this->id ?? false;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

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