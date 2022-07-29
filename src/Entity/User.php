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

    #[Column(name: 'username', type: Type::STRING)]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Assert\MinLength(message: "Le nom d'utilisateur doit faire au moins 3 caractères", min: 3)]
    #[Assert\MaxLength(message: "Le nom d'utilisateur ne peut excéder 20 caractères", max: 20)]
    #[Assert\Unique(User::class, 'username', "Un utilisateur existe déjà avec cet username")]
    #[Assert\Username()]
    private string $username = '';

    #[Column(name: 'email', type: Type::STRING)]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Email("L'adresse email n'est pas valide")]
    #[Assert\Unique(User::class, 'email', "L'adresse email est déjà utilisée")]
    private string $email = '';

    #[Column(name: 'password', type: Type::STRING)]
    private string $password = '';

    #[Assert\MaxLength(message: "Le mot de passe ne peut excéder 255 caractères", max: 255)]
    #[Assert\MinLength(message: "Le mot de passe doit faire au moins 8 caractères", min: 8)]
    #[Assert\StrongPassword()]
    #[Assert\HIBP()]
    private ?string $plainPassword;

    #[Column(name: 'remember_token', type: Type::STRING)]
    private ?string $rememberToken;

    #[Column(name: 'enabled')]
    private int $enabled = 0;

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

    public function getRememberToken(): ?string
    {
        return $this->rememberToken;
    }

    public function setRememberToken(?string $rememberToken): User
    {
        $this->rememberToken = $rememberToken;
        return $this;
    }

    public static function encodePassword(string $plainPassword): string
    {
        return password_hash($plainPassword, PASSWORD_ARGON2I);
    }

    public function comparePassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->getPassword());
    }

    public function sanitize(): void
    {
        $this->plainPassword = '';
    }

    public function getEnabled(): int
    {
        return $this->enabled;
    }

    public function setEnabled(int $enabled): User
    {
        $this->enabled = $enabled;
        return $this;
    }
}
