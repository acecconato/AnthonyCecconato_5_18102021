<?php

declare(strict_types=1);

namespace Blog\Entity;

use Blog\ORM\Mapping\Attribute\Column;
use Blog\ORM\Mapping\Attribute\Entity;
use Blog\ORM\Mapping\Attribute\Enum\Type;
use Blog\ORM\Mapping\Attribute\Id;
use Blog\ORM\Mapping\Attribute\Table;
use Blog\Repository\CommentRepository;
use Blog\Validator\Constraint as Assert;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;

#[Entity(repositoryClass: CommentRepository::class)]
#[Table(tableName: 'comment')]
class Comment
{
    #[Id()]
    #[Assert\Uuid()]
    protected string $id;

    #[Column(name: 'content')]
    #[Assert\NotBlank("Le commentaire ne doit pas être vide")]
    #[Assert\NotNull("Le commentaire ne doit pas être vide")]
    #[Assert\MaxLength(message: "Le commentaire ne peut excéder 500 caractères", max: 500)]
    #[Assert\MinLength(message: "Le commentaire doit faire au moins 3 caractères", min: 3)]
    private string $content;

    #[Column(name: 'created_at', type: Type::DATE)]
    private DateTime $createdAt;

    #[Column(name: 'user_id')]
    #[Assert\Uuid()]
    private ?string $userId = null;

    #[Column(name: 'post_id')]
    #[Assert\Uuid()]
    private string $postId;

    #[Column(name: 'enabled')]
    private int $enabled = 0;

    private User $user;

    private Post $post;

    public function __construct()
    {
        if (! isset($this->id)) {
            $this->id = (string)Uuid::uuid4();
        }
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): Comment
    {
        $this->id = $id;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): Comment
    {
        $this->content = $content;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): Comment
    {
        $this->userId = $userId;

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
    public function setCreatedAt(?DateTime $createdAt): Comment
    {
        $this->createdAt = new DateTime();

        if ($createdAt) {
            $this->createdAt = $createdAt;
        }

        return $this;
    }

    public function setUser(User $user): Comment
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPostId(): string
    {
        return $this->postId;
    }

    public function setPostId(string $postId): Comment
    {
        $this->postId = $postId;

        return $this;
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): Comment
    {
        $this->post = $post;

        return $this;
    }

    public function getEnabled(): int
    {
        return $this->enabled;
    }

    public function setEnabled(int $enabled): Comment
    {
        $this->enabled = $enabled;

        return $this;
    }
}
