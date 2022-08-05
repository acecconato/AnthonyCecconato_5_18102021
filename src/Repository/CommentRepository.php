<?php

declare(strict_types=1);

namespace Blog\Repository;

class CommentRepository extends Repository
{
    public function countAwaitingValidation(): int
    {
        $query = "SELECT COUNT(*) FROM comment WHERE enabled='0'";
        return (int)$this->getAdapter()->query($query)->fetchColumn();
    }
}
