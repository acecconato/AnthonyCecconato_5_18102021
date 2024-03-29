<?php

declare(strict_types=1);

namespace Blog\ORM;

use Blog\Database\Adapter\AdapterInterface;
use Blog\Hydration\HydratorInterface;
use Blog\Hydration\ObjectHydrator;
use Blog\ORM\Mapping\MapperInterface;
use PDO;
use ReflectionClass;
use ReflectionException;

class EntityManager
{
    private UnitOfWork $unitOfWork;

    public function __construct(
        private readonly AdapterInterface $adapter,
        private readonly MapperInterface $mapper,
        private readonly ObjectHydrator $hydrator
    ) {
        $this->unitOfWork = new UnitOfWork($this, $this->mapper);
    }

    public function add(object $entity): self
    {
        $this->unitOfWork->add($entity);
        return $this;
    }

    public function update(object $entity): self
    {
        $this->unitOfWork->update($entity);
        return $this;
    }

    public function delete(object $entity): self
    {
        $this->unitOfWork->delete($entity);
        return $this;
    }

    public function flush(): int
    {
        return $this->unitOfWork->commit();
    }

    public function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }

    public function getHydrator(): HydratorInterface
    {
        return $this->hydrator;
    }

    /**
     * @return array<object>
     * @throws ReflectionException
     */
    public function findAll(string $entityFqcn, int $offset, int $limit, string $orderBy, string $orderWay): array
    {
        $mapping = $this->mapper->resolve($entityFqcn);

        $tableName = $mapping->getTable()->tableName;

        $query = "SELECT * FROM  $tableName ORDER BY $orderBy $orderWay";
        $query .= ((bool)$limit) ? " LIMIT $limit" : null;
        $query .= ((bool)$offset) ? " OFFSET $offset" : null;

        $rawResults = $this->adapter->query($query)->fetchAll(PDO::FETCH_ASSOC);

        return $this->hydrator->hydrateResultSet($rawResults, $entityFqcn);
    }

    /**
     * @throws ReflectionException
     */
    public function find(string $entityFqcn, string $id): object|false
    {
        $mapping = $this->mapper->resolve($entityFqcn);

        $tableName = $mapping->getTable()->tableName;
        $idColumn = $mapping->getId();

        $query = "SELECT * FROM $tableName WHERE $idColumn='" . $id . "'";

        $rawResult = $this->adapter->query($query)->fetch(PDO::FETCH_ASSOC);

        if (!$rawResult) {
            return false;
        }

        $object = (new ReflectionClass($entityFqcn))->newInstance();
        return $this->hydrator->hydrateSingle($rawResult, $object);
    }

    /**
     * @param  string        $entityFqcn
     * @param  array<string> $criteria
     * @return object|false
     * @throws ReflectionException
     */
    public function findOneBy(string $entityFqcn, array $criteria = []): object|false
    {
        $mapping = $this->mapper->resolve($entityFqcn);

        $tableName = $mapping->getTable()->tableName;

        $query = "SELECT * FROM $tableName";

        foreach ($criteria as $key => $criterion) {
            if ($key === array_key_first($criteria)) {
                $query .= " WHERE $key='" . $criterion . "'";
            } else {
                $query .= " AND $key='" . $criterion . "'";
            }
        }

        $rawResult = $this->adapter->query($query)->fetch(PDO::FETCH_ASSOC);

        if (!$rawResult) {
            return false;
        }

        $object = (new ReflectionClass($entityFqcn))->newInstance();
        return $this->hydrator->hydrateSingle($rawResult, $object);
    }

    /**
     * @param  array<mixed> $criteria
     * @return object[]
     * @throws ReflectionException
     */
    public function findAllBy(
        string $entityFqcn,
        array $criteria = [],
        ?string $orderBy = null,
        ?string $orderWay = null
    ): array {
        $mapping = $this->mapper->resolve($entityFqcn);

        $tableName = $mapping->getTable()->tableName;

        $query = "SELECT * FROM $tableName";

        foreach ($criteria as $key => $criterion) {
            if ($key === array_key_first($criteria)) {
                $query .= " WHERE $key='" . $criterion . "'";
            } else {
                $query .= " AND $key='" . $criterion . "'";
            }
        }

        ($orderBy) ? $query .= " ORDER BY $orderBy " . ($orderWay ?? 'DESC') : null;

        $rawResult = $this->adapter->query($query)->fetchAll(PDO::FETCH_ASSOC);

        if (!$rawResult) {
            return [];
        }

        return $this->hydrator->hydrateResultSet($rawResult, $entityFqcn);
    }

    public function count(string $entityFqcn, string $column, string $value): int
    {
        $mapping = $this->mapper->resolve($entityFqcn);

        $tableName = $mapping->getTable()->tableName;

        $query = "SELECT COUNT( " . $mapping->getId() . " ) FROM $tableName 
                WHERE $column=:value";

        $result = $this->adapter->query($query, [':value' => $value]);

        return $result->fetch(PDO::FETCH_COLUMN);
    }

    public function countAll(string $entityFqcn): int
    {
        $mapping = $this->mapper->resolve($entityFqcn);

        $tableName = $mapping->getTable()->tableName;

        $query = "SELECT COUNT(*) FROM $tableName";

        $result = $this->adapter->query($query);

        return (int)$result->fetch(PDO::FETCH_COLUMN);
    }

    /**
     * @param  string        $entityFqcn
     * @param  array<string> $ids
     * @return int
     */
    public function deleteById(string $entityFqcn, array $ids): int
    {
        $mapping = $this->mapper->resolve($entityFqcn);

        $tableName = $mapping->getTable()->tableName;

        foreach ($ids as $id) {
            $query = "DELETE FROM $tableName WHERE " . $mapping->getId() . " = :id";
            $this->adapter->addToTransaction($query, [':id' => $id]);
        }

        return $this->adapter->transactionQuery();
    }
}
