<?php

namespace Blog\Entity;

use Blog\Validator\Constraint\MaxLength;
use Blog\Validator\Constraint\MinLength;
use Blog\Validator\Constraint\NotBlank;
use Blog\Validator\Constraint\NotNull;

class Login
{
    #[NotNull()]
    #[NotBlank()]
    #[MinLength(message: "Le nom d'utilisateur doit faire au moins 3 caractères", min: 3)]
    #[MaxLength(message: "Le nom d'utilisateur ne peut excéder 20 caractères", max: 20)]
    private string $username;

    #[NotNull()]
    #[NotBlank()]
    #[MaxLength(message: "Le mot de passe ne peut excéder 255 caractères", max: 255)]
    private string $password;

    private bool $rememberMe = false;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): Login
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): Login
    {
        $this->password = $password;
        return $this;
    }

    public function getRememberMe(): bool
    {
        return $this->rememberMe;
    }

    public function setRememberMe(bool $rememberMe): User
    {
        $this->rememberMe = $rememberMe;
        return $this;
    }
}