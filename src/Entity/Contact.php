<?php

declare(strict_types=1);

namespace Blog\Entity;

use Blog\Validator\Constraint\Email;
use Blog\Validator\Constraint\MaxLength;
use Blog\Validator\Constraint\MinLength;
use Blog\Validator\Constraint\NotBlank;
use Blog\Validator\Constraint\NotNull;

class Contact
{
    #[NotNull]
    #[NotBlank("Le nom ne doit pas être vide")]
    #[MaxLength(message: "Le nom ne doit pas excéder 30 caractères", max: 30)]
    #[MinLength(message: "Le nom doit faire au moins 3 caractères", min: 3)]
    private string $name;

    #[NotNull]
    #[NotBlank("L'adresse email ne doit pas être vide")]
    #[Email("L'adresse email n'est pas valide")]
    private string $email;

    #[NotNull]
    #[NotBlank("Le message ne doit pas être vide")]
    #[MinLength("Le message doit faire au moins 10 caractères")]
    #[MaxLength(message: "Le message ne peut excéder 1000 caractères", max: 1000)]
    private string $message;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Contact
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): Contact
    {
        $this->message = $message;
        return $this;
    }
}