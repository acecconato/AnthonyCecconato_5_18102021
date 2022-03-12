<?php

declare(strict_types=1);

namespace Blog\Database\Adapter;

use PDO;

interface AdapterInterface
{
    /** Connect to the database */
    public function connect(): mixed;

    /** Execute a raw database query
     * @param array<string> $bind
     */
    public function query(string $rawQuery, array $bind = []): mixed;

    public function getConnection(): PDO;

    /** Execute the transaction */
    public function transactionQuery(): int;

    /** Add a query and its bind to the transaction
     * @param array<string> $bind
     */
    public function addToTransaction(string $query, array $bind = []): self;
}