<?php

namespace Blog\Entity;

use Blog\ORM\Mapping\Attribute\Id;
use Ramsey\Uuid\Uuid;

abstract class AbstractEntity
{
    #[Id()]
    protected string $id;

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
}