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
    #[Assert\Uuid()]
    protected string $id;

    #[Column(name: 'username', type: Type::STRING, nullable: false, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\MaxLength(
        message: "Le nom d'utilisateur '%s' ne peut excéder %d caractères. Il en possède actuellement %d",
        maxLen: 20)
    ]
    #[Assert\MinLength(
        message: "Le nom d'utilisateur '%s' doit faire au moins %d caractères. Il en possède actuellement %d",
        minLength: 3)
    ]
    private string $username = '';

    #[Column(name: 'email', type: Type::STRING, nullable: false, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Email()]
    private string $email = '';

    #[Column(name: 'password', type: Type::STRING, nullable: false)]
    private string $password = '';

    private ?string $plainPassword;

    public function __construct()
    {
        if (!isset($this->id)) {
            $this->id = (string)Uuid::uuid4();
        }

        $this->sanitize();
    }

    public function getId(): string|false
    {
        return $this->id ?? false;
    }

    public function setId(string $id): User
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        $this->sanitize();
        return $this;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public static function encodePassword(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_ARGON2I);
    }

    public function sanitize(): void
    {
        $this->plainPassword = '';
    }
}