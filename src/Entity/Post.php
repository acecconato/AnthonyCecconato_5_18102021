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
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Entity(repositoryClass: PostRepository::class)]
#[Table(tableName: 'post')]
class Post
{
    #[Id()]
    #[Assert\Uuid()]
    protected string $id;

    #[Column(name: 'title')]
    #[Assert\NotBlank('Le titre ne doit pas être vide')]
    #[Assert\NotNull('Le titre ne doit pas être vide')]
    #[Assert\MinLength(message: 'Le titre doit contenir au moins 10 caractères', min: 10)]
    #[Assert\MaxLength(message: 'Le titre ne peut excéder 255 caractères', max: 255)]
    private string $title;

    #[Column(name: 'filename')]
    private ?string $filename = null;

    #[Assert\Image("L'image n'est pas valide")]
    private ?UploadedFile $file = null;

    #[Column(name: 'content')]
    #[Assert\NotBlank('Le contenu ne doit pas être vide')]
    #[Assert\NotNull('Le contenu ne doit pas être vide')]
    #[Assert\MaxLength(max: 10000)]
    private string $content;

    #[Column(name: 'excerpt')]
    #[Assert\MaxLength(message: 'Le résumé ne peut excéder 300 caractères', max: 300)]
    private ?string $excerpt = null;

    #[Column(name: 'slug')]
    #[Assert\NotBlank('Le slug ne doit pas être vide')]
    #[Assert\NotNull('Le slug ne doit pas être vide')]
    #[Assert\Slug('Le slug est invalide')]
    #[Assert\MaxLength(message: 'Le slug ne peut excéder 255 caractères', max: 255)]
    #[Assert\Unique(entityFqcn: Post::class, column: 'slug', message: "Le slug '%s' existe déjà")]
    private string $slug;

    #[Column(name: 'created_at', type: Type::DATE)]
    private DateTime $createdAt;

    #[Column(name: 'updated_at', type: Type::DATE)]
    private ?DateTime $updatedAt = null;

    #[Column(name: 'user_id')]
    #[Assert\Uuid()]
    private ?string $userId = null;

    private User $user;

    /** @var Comment[] */
    private array $comments = [];

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

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): Post
    {
        $this->filename = $filename;
        return $this;
    }

    public function setFile(?UploadedFile $file): Post
    {
        $this->file = $file;
        return $this;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
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
        if ($slug) {
            $this->slug = $slug;
        }

        if (!$slug && $this->title) {
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

    public function setUser(User $user): Post
    {
        $this->user = $user;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param Comment[] $comments
     */
    public function setComments(array $comments): Post
    {
        $this->comments = $comments;
        return $this;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }
}
