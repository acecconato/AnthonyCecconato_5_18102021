<?php

declare(strict_types=1);

namespace Blog\Repository;

use Blog\Entity\Post;
use PDO;

class PostRepository extends Repository
{
    /**
     * @return Post[]
     */
    public function getPostsWithUsers(
        int $offset = 0,
        int $limit = 0,
        string $orderBy = 'created_at',
        string $orderWay = 'DESC'
    ): array {
        $query = "
            SELECT * 
            FROM post p
            INNER JOIN user u ON u.id = p.user_id
            ORDER BY $orderBy $orderWay
            ";

        $query .= ((bool)$limit) ? " LIMIT $limit" : null;
        $query .= ((bool)$offset) ? " OFFSET $offset" : null;

        $results = $this->query($query)->fetchAll(PDO::FETCH_ASSOC);

        return $this->getHydrator()->hydrateResultSet($results, Post::class);
    }
}