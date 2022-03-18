<?php

declare(strict_types=1);

namespace Blog\Entity;

use Blog\ORM\Mapping\Attribute\Column;
use Blog\ORM\Mapping\Attribute\Entity;
use Blog\ORM\Mapping\Attribute\Id;
use Blog\ORM\Mapping\Attribute\Table;
use Blog\Repository\PostRepository;

#[Entity(repositoryClass: PostRepository::class)]
#[Table(tableName: 'post')]
class Post extends AbstractEntity
{
    #[Column(name: 'title')]
    private string $title;

    #[Column(name: 'content')]
    private string $content;

    #[Column(name: 'user_id')]
    private string $userId;

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