<?php

declare(strict_types=1);

namespace Blog\Repository;

use Blog\Entity\Comment;
use Blog\Entity\User;
use ReflectionException;

class CommentRepository extends Repository
{
    /**
     * @throws ReflectionException
     */
    public function loadUser(Comment $comment): void
    {
        $userId = $comment->getUserId();

        /** @var User $user */
        $user = $this->getEntityManager()->find(User::class, $userId);
        $comment->setUser($user);
    }
}
