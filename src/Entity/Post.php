<?php

declare(strict_types=1);

namespace Blog\Entity;

use Blog\ORM\Mapping\Attribute\Column;
use Blog\ORM\Mapping\Attribute\Entity;
use Blog\ORM\Mapping\Attribute\Enum\Type;
use Blog\ORM\Mapping\Attribute\Id;
use Blog\ORM\Mapping\Attribute\Table;
use Blog\Repository\PostRepository;
use Blog\Validator\Constraint as Assert;
use Cocur\Slugify\Slugify;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;

#[Entity(repositoryClass: PostRepository::class)]
#[Table(tableName: 'post')]
class Post
{
    #[Id()]
    #[Assert\Uuid()]
    protected string $id;

    #[Column(name: 'title')]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\MinLength(minLength: 10)]
    #[Assert\MaxLength(maxLength: 255)]
    private string $title = '';

    #[Column(name: 'content')]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\MaxLength(maxLength: 10000)]
    private string $content = '';

    #[Column(name: 'excerpt')]
    #[Assert\NotBlank()]
    #[Assert\MaxLength(maxLength: 300)]
    private ?string $excerpt = null;

    #[Column(name: 'slug')]
    #[Assert\NotBlank()]
    #[Assert\NotNull()]
    #[Assert\Slug()]
    #[Assert\MaxLength(maxLength: 255)]
    #[Assert\Unique(entityFqcn: Post::class, column: 'slug', message: "Le slug '%s' existe déjà")]
    private string $slug = '';

    #[Column(name: 'created_at', type: Type::DATE)]
    private DateTime $createdAt;

    #[Column(name: 'updated_at', type: Type::DATE)]
    private ?DateTime $updatedAt = null;

    #[Column(name: 'user_id')]
    #[Assert\Uuid()]
    private ?string $userId = null;

    public function __construct()
    {
        if (!isset($this->id)) {
            $this->id = (string)Uuid::uuid4();
        }
    }

    public function getId(): ?string
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

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string|null $userId): Post
    {
        $this->userId = $userId;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): Post
    {
        $this->slug = $slug;

        if (!$slug) {
            $slugify = Slugify::create();
            $this->slug = $slugify->slugify($this->title);
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getCreatedAt(): DateTime
    {
        $createdAt = new DateTime();

        if (isset($this->createdAt)) {
            $createdAt = $this->createdAt;
        }

        return $createdAt;
    }

    /**
     * @throws Exception
     */
    public function setCreatedAt(?DateTime $createdAt): Post
    {
        $this->createdAt = new DateTime();

        if ($createdAt) {
            $this->createdAt = $createdAt;
        }

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @throws Exception
     */
    public function setUpdatedAt(?DateTime $updatedAt): Post
    {
        $this->updatedAt = new DateTime();

        if ($updatedAt) {
            $this->updatedAt = $updatedAt;
        }

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(?string $excerpt): Post
    {
        $this->excerpt = $excerpt;
        return $this;
    }
}