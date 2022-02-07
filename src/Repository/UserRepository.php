<?php

declare(strict_types=1);

namespace Blog\Repository;

use Blog\Database\Adapter\MySQLAdapter;

class UserRepository extends Repository
{
//    /** Create a new user
//     * @param string $username
//     * @param string $email
//     * @return false|string lastInsertId
//     */
//    public function create(string $username, string $email): false|string
//    {
//        /** @var MySQLAdapter $adapter */
//        $adapter = $this->getAdapter();
//
//        $uuid = $this->generateId();
//
//        $statement = $adapter->query(
//            "INSERT INTO `user` VALUES (:id, :username, :email)",
//            [
//                ':id' => $uuid,
//                ':username' => strip_tags($username),
//                ':email' => strip_tags($email)
//            ]
//        );
//
//        if ($statement->rowCount()) {
//            return $uuid;
//        }
//
//        return false;
//    }
}