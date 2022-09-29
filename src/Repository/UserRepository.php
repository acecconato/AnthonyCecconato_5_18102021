<?php

declare(strict_types=1);

namespace Blog\Repository;

use Blog\Entity\User;
use PDO;

class UserRepository extends Repository
{
    /**
     * @param  string $search
     * @return User|null
     */
    public function getUserByUsernameOrEmail(string $search): ?object
    {
        $em = $this->getEntityManager();

        $adapter = $em->getAdapter();
        $hydrator = $em->getHydrator();

        $bind = [
            ':username' => $search,
            ':email' => $search
        ];

        $statement = $adapter->query(
            'SELECT * FROM user
            WHERE username=:username OR email=:email',
            $bind
        );

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return $hydrator->hydrateSingle($result, new User());
    }

    public function countUserAwaitingValidation(): int
    {
        $query = "SELECT COUNT(*) FROM user WHERE enabled='0'";
        return (int)$this->getAdapter()->query($query)->fetchColumn();
    }
}
