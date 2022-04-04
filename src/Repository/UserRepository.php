<?php

declare(strict_types=1);

namespace Blog\Repository;

use Blog\Entity\Post;

class UserRepository extends Repository
{
    /** @return array<Post> */
    public function getUserPosts(string $userId) : array
    {
        return [];
    }
}