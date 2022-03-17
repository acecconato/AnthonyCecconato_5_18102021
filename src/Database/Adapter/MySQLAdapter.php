<?php

declare(strict_types=1);

namespace Blog\Database\Adapter;

use PDO;
use PDOException;
use PDOStatement;

class MySQLAdapter implements AdapterInterface
{
    private PDO|null $connection = null;

    /** @var array<array-key, array{query: string, bind: array<string>}> */
    private array $transactions;

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

    public function connect(): PDO
    {
        $this->connection = new PDO(
            "mysql:host=$this->host;dbname=$this->dbName",
            $this->dbUser,
            $this->dbPassword,
            [PDO::FETCH_ASSOC]
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

    public function query(string $rawQuery, array $bind = []): PDOStatement|false
    {
        $this->connect();

        $statement = $this->connection->prepare($rawQuery);

        $statement->execute($bind);

        return $statement;
    }

    public function transactionQuery(): int
    {
        try {
            $this->connect();
            $this->connection->beginTransaction();

            $rowCount = 0;
            foreach ($this->transactions as $transaction) {
                $query = $transaction['query'];
                $bind = $transaction['bind'];

                $statement = $this->connection->prepare($query);
                $statement->execute($bind);
                $rowCount += $statement->rowCount();
            }

            $this->connection->commit();
            $this->clearTransaction();

            return $rowCount;
        } catch (PDOException $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function addToTransaction(string $query, array $bind = []): self
    {
        $this->transactions[] = ['query' => $query, 'bind' => $bind];
        return $this;
    }

    public function clearTransaction(): void
    {
        unset($this->transactions);
        $this->transactions = [];
    }
}