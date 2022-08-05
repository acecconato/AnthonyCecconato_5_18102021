<?php

namespace Blog\Entity;

use Blog\Validator\Constraint as Assert;

class ResetPassword
{
    #[Assert\NotNull("L'identifiant ne doit pas être vide")]
    #[Assert\NotBlank("L'identifiant ne doit pas être vide")]
    #[Assert\MaxLength("L'identifiant ne doit pas excéder 255 caractères")]
    private string $username;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): ResetPassword
    {
        $this->username = $username;
        return $this;
    }
}
