<?php

namespace Blog\Entity;

use Blog\Validator\Constraint\Email;
use Blog\Validator\Constraint\HIBP;
use Blog\Validator\Constraint\MaxLength;
use Blog\Validator\Constraint\MinLength;
use Blog\Validator\Constraint\NotBlank;
use Blog\Validator\Constraint\NotNull;
use Blog\Validator\Constraint\StrongPassword;
use Blog\Validator\Constraint\Unique;
use Blog\Validator\Constraint\Username;

class Register
{
    #[NotNull()]
    #[NotBlank()]
    #[MinLength(message: "Le nom d'utilisateur doit faire au moins 3 caractères", min: 3)]
    #[MaxLength(message: "Le nom d'utilisateur ne peut excéder 20 caractères", max: 20)]
    #[Unique(User::class, 'username', "Un utilisateur existe déjà avec cet username")]
    #[Username()]
    private string $username;

    #[NotNull()]
    #[NotBlank()]
    #[MaxLength(message: "Le mot de passe ne peut excéder 255 caractères", max: 255)]
    #[MinLength(message: "Le mot de passe doit faire au moins 8 caractères", min: 8)]
    #[StrongPassword()]
    #[HIBP()]
    private string $password;

    #[NotNull()]
    #[NotBlank()]
    #[Email("L'adresse email n'est pas valide")]
    #[Unique(User::class, 'email', "L'adresse email est déjà utilisée")]
    private string $email;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): Register
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): Register
    {
        $this->password = $password;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Register
    {
        $this->email = $email;
        return $this;
    }
}