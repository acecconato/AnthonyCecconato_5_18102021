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
    #[NotBlank]
    #[MaxLength(max: 30)]
    #[MinLength(min: 3)]
    private string $name;

    #[NotNull]
    #[NotBlank]
    #[Email()]
    private string $email;

    #[NotNull]
    #[NotBlank]
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