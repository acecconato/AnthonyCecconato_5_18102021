<?php

declare(strict_types=1);

namespace Blog\Entity;

use Blog\ORM\Mapping\Attribute\Column;
use Blog\ORM\Mapping\Attribute\Entity;
use Blog\ORM\Mapping\Attribute\Enum\Type;
use Blog\ORM\Mapping\Attribute\Id;
use Blog\ORM\Mapping\Attribute\Table;
use Blog\Repository\PostRepository;
use DateTime;
use Ramsey\Uuid\Uuid;

#[Entity(repositoryClass: PostRepository::class)]
#[Table(tableName: 'post')]
class Post
{
    #[Id()]
    #[Column(name: 'id', unique: true)]
    protected string $id;

    #[Column(name: 'title', unique: true)]
    private string $title;

    #[Column(name: 'content')]
    private string $content;

    #[Column(name: 'excerpt', nullable: true)]
    private ?string $excerpt = null;

    #[Column(name: 'slug', unique: true)]
    private string $slug;

    #[Column(name: 'created_at', type: Type::DATETIME, nullable: true)]
    private ?string $createdAt = null;

    #[Column(name: 'updated_at', type: Type::DATETIME, nullable: true)]
    private ?string $updatedAt = null;

    #[Column(name: 'user_id', nullable: true)]
    private ?string $userId = null;

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

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string|null $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt ?? new DateTime();
        return $this;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(?string $excerpt): self
    {
        $this->excerpt = $excerpt;
        return $this;
    }
}