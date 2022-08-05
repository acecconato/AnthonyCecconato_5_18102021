<?php

namespace Blog\Entity;

use Blog\Validator\Constraint as Assert;

class HandleResetPassword
{
    private string $password = '';

    #[Assert\MaxLength(message: "Le mot de passe ne peut excéder 255 caractères", max: 255)]
    #[Assert\MinLength(message: "Le mot de passe doit faire au moins 8 caractères", min: 8)]
    #[Assert\StrongPassword()]
    #[Assert\HIBP()]
    private ?string $plainPassword;

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): HandleResetPassword
    {
        $this->password = $password;
        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): HandleResetPassword
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }
}
