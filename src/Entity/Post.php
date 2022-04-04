<?php

declare(strict_types=1);

namespace Blog\Entity;

use Blog\ORM\Mapping\Attribute\Column;
use Blog\ORM\Mapping\Attribute\Entity;
use Blog\ORM\Mapping\Attribute\Id;
use Blog\ORM\Mapping\Attribute\Table;
use Blog\Repository\PostRepository;
use Ramsey\Uuid\Uuid;

#[Entity(repositoryClass: PostRepository::class)]
#[Table(tableName: 'post')]
class Post
{
    #[Id()]
    protected string $id;

    #[Column(name: 'title')]
    private string $title;

    #[Column(name: 'content')]
    private string $content;

    #[Column(name: 'user_id')]
    private string|null $userId;

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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Post
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): Post
    {
        $this->content = $content;
        return $this;
    }

    public function getUserId(): string|null
    {
        return $this->userId;
    }

    public function setUserId(string|null $userId): Post
    {
        $this->userId = $userId;
        return $this;
    }
}