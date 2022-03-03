<?php

declare(strict_types=1);

namespace Blog\Database\Adapter;

use PDO;
use PDOException;
use PDOStatement;

class MySQLAdapter implements AdapterInterface
{
    private PDO $connection;

    /**
     * @param string $host
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPassword
     */
    public function __construct(
        private string $host,
        private string $dbName,
        private string $dbUser,
        private string $dbPassword
    ) {
    }

    /** @inheritDoc */
    public function connect(): PDO
    {
        $this->setConnection(
            new PDO(
                "mysql:host=$this->host;dbname=$this->dbName",
                $this->dbUser,
                $this->dbPassword,
                [PDO::FETCH_ASSOC]
            )
        );

        return $this->connection;
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @param PDO $connection
     * @return MySQLAdapter
     */
    public function setConnection(PDO $connection): self
    {
        $this->connection = $connection;

        return $this;
    }

    /** @inheritDoc */
    public function query(string $rawQuery, array $bind = []): PDOStatement|false
    {
        $this->connect();

        $statement = $this->connection->prepare($rawQuery);

        $statement->execute($bind);

        return $statement;
    }

    /**
     * @param array<string, array<string>> $queries
     * @return void
     */
    public function transactionQuery(array $queries): void
    {
        try {
            $this->connect();
            $this->connection->beginTransaction();

            foreach ($queries as $query) {
                $statement = $this->connection->prepare($query[0]);
                $statement->execute($query[1] ?? []);
            }

            $this->connection->commit();
        } catch (PDOException $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}