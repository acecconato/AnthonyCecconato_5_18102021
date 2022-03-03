<?php

declare(strict_types=1);

namespace Blog\Database\Adapter;

use PDO;

interface AdapterInterface
{
    /** Connect to a database
     * @return mixed
     */
    public function connect(): mixed;

    /** Execute a database query
     * @param string $rawQuery
     * @param array<string> $bind
     * @return mixed
     */
    public function query(string $rawQuery, array $bind = []): mixed;

    public function getConnection(): PDO;

    /**
     * @param array<string, array<string>> $queries
     * @return void
     */
    public function transactionQuery(array $queries): void;
}