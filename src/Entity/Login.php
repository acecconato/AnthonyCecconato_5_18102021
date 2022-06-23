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
    #[MinLength(message: "Le nom d'utilisateur '%s' doit faire au moins %d caractères.", min: 3)]
    #[MaxLength(message: "Le nom d'utilisateur '%s' ne peut excéder %d caractères. Actuel : %d", max: 20)]
    private string $username;

    #[NotNull()]
    #[NotBlank()]
    #[MaxLength(message: "Le mot de passe ne peut excéder 255 caractères", max: 255)]
    private string $password;

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
}