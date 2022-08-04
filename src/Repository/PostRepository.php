<?php

declare(strict_types=1);

namespace Blog\Repository;

use Blog\Entity\Comment;
use Blog\Entity\Post;
use Blog\Entity\User;
use ReflectionException;

class PostRepository extends Repository
{
    /**
     * @return Post[]
     * @throws ReflectionException
     */
    public function getPostsWithUsers(
        int $offset = 0,
        int $limit = 0,
        string $orderBy = 'created_at',
        string $orderWay = 'DESC'
    ): array {
        /** @var Post[] $posts */
        $posts = $this->findAll($offset, $limit, $orderBy, $orderWay);
        foreach ($posts as $post) {
            /** @var User $user */
            $user = $this->getEntityManager()->find(User::class, $post->getUserId());
            $post->setUser($user);
        }

        return $posts;
    }
}
