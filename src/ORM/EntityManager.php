<?php

declare(strict_types=1);

namespace Blog\ORM;

use Blog\Database\Adapter\AdapterInterface;
use Blog\Hydration\HydratorInterface;
use Blog\Hydration\ObjectHydrator;
use Blog\ORM\Mapping\MapperInterface;
use Blog\Validator\Validator;
use PDO;
use ReflectionException;

class EntityManager
{
    private UnitOfWork $unitOfWork;

    public function __construct(
        private AdapterInterface $adapter,
        private MapperInterface $mapper,
        private ObjectHydrator $hydrator
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
    public function findAll(string $entityFqcn, string $orderBy, string $orderWay): array
    {
        $mapping = $this->mapper->resolve($entityFqcn);

        $tableName = $mapping->getTable()->tableName;

        $query = "SELECT * FROM  $tableName ORDER BY $orderBy $orderWay";
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

        return $this->hydrator->hydrateSingle($rawResult, $entityFqcn);
    }

    /**
     * @param string $entityFqcn
     * @param array<string> $criteria
     * @return object|false
     * @throws ReflectionException
     */
    public function findOneBy(string $entityFqcn, array $criteria = []): object|false
    {
        $mapping = $this->mapper->resolve($entityFqcn);

        $tableName = $mapping->getTable()->tableName;
        array_walk($criteria, fn(&$criterion) => $criterion = $criterion);

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

        return $this->hydrator->hydrateSingle($rawResult, $entityFqcn);
    }

    public function count(string $entityFqcn, string $column, string $value): int
    {
        $mapping = $this->mapper->resolve($entityFqcn);

        $tableName = $mapping->getTable()->tableName;

        $query = "SELECT COUNT( " . $mapping->getId() . " ) FROM $tableName 
                WHERE $column=:value";

        $result = $this->adapter->query($query, [':value' => $value]);

        dd($result->fetch(PDO::FETCH_COLUMN));
    }

    /**
     * @param string $entityFqcn
     * @param array<string> $ids
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