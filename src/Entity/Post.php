<?php

declare(strict_types=1);

namespace Blog\Entity;

use Blog\Attribute\Column;
use Blog\Attribute\Entity;
use Blog\Attribute\Id;
use Blog\Attribute\Table;
use Blog\Repository\PostRepository;

#[Entity(repositoryClass: PostRepository::class)]
#[Table(tableName: 'post')]
class Post
{
    #[Id()]
    private string $id;

    #[Column(name: 'title')]
    private string $title;

    #[Column(name: 'content')]
    private string $content;

    #[Column(name: 'user_id')]
    private string $userId;


    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Post
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

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): Post
    {
        $this->userId = $userId;
        return $this;
    }
}