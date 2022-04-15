<?php

declare(strict_types=1);

namespace Blog\ORM;

use Blog\ORM\Exception\NullableConstraintException;
use Blog\ORM\Exception\UniqueConstraintException;
use Blog\ORM\Mapping\Attribute\Column;
use Blog\ORM\Mapping\MapperInterface;
use Exception;
use Ramsey\Uuid\Uuid;

class UnitOfWork
{
    /** @var array<int, object> */
    private array $entityInsertions = [];

    /** @var array<int, object> */
    private array $entityUpdates = [];

    /** @var array<string> */
    private array $entityDeletions = [];

    public function __construct(
        private EntityManager $entityManager,
        private MapperInterface $mapper
    ) {
    }

    private function executeInserts(): int
    {
        $adapter = $this->entityManager->getAdapter();

        foreach ($this->entityInsertions as $object) {
            if (!is_object($object)) {
                throw new Exception("$object is not a valid object");
            }

            $mapping = $this->mapper->resolve($object::class);
            $tableName = $mapping->getTable()->tableName;

            $prepValues = array_map(fn($column) => ':' . $column->name, $mapping->getColumns());

            foreach ($mapping->getColumns() as $column) {
                if ($column->unique) {
                    $this->checkUniqueConstraint($tableName, $column, $object);
                }

                if (!$column->nullable) {
                    $this->checkNullableConstraint($tableName, $column, $object);
                }
            }

            die();

            $query = "
                INSERT INTO $tableName 
                VALUES ( :" . $mapping->getId() . ", " . implode(', ', $prepValues) . " )
            ";

            $bind = [];

            ($object->getId()) ?: $object->setId((string)Uuid::uuid4());
            $bind[':' . $mapping->getId()] = $object->getId();

            foreach ($mapping->getColumns() as $column) {
                // @phpstan-ignore-next-line
                $bind[':' . $column->name] = $object->{'get' . ucfirst($column->propertyName)}();
            }

            $adapter->addToTransaction($query, $bind);
        }

        $this->clearEntityInsertionsQueue();
        return $adapter->transactionQuery();
    }

    /**
     * @param string $tableName
     * @param Column $column
     * @param object $object
     * @throws NullableConstraintException
     */
    private function checkNullableConstraint(string $tableName, Column $column, object $object): void
    {
        // @phpstan-ignore-next-line
        if (null === $object->{'get' . ucfirst($column->propertyName)}()) {
            throw new NullableConstraintException("'" . $column->name ."' cannot be nullable");
        }
    }

    /**
     * @param string $tableName
     * @param Column $column
     * @param object $object
     * @return void
     * @throws UniqueConstraintException
     */
    private function checkUniqueConstraint(string $tableName, Column $column, object $object): void
    {
        $adapter = $this->entityManager->getAdapter();

        // @phpstan-ignore-next-line
        $value = $object->{'get' . ucfirst($column->propertyName)}();

        $query = "SELECT COUNT(*) FROM $tableName WHERE " . $column->name . "=:columnName";
        $bind = [':columnName' => $value];

        $statement = $adapter->query($query, $bind);

        if ($statement->rowCount() > 0) {
            throw new UniqueConstraintException("'$value' already exists in the database");
        }
    }

    /**
     * @return int
     * @throws Exception
     */
    private function executeUpdates(): int
    {
        $adapter = $this->entityManager->getAdapter();

        /** @var object $object */
        foreach ($this->entityUpdates as $object) {
            if (!is_object($object)) {
                throw new Exception("$object is not a valid object");
            }

            $mapping = $this->mapper->resolve($object::class);
            $tableName = $mapping->getTable()->tableName;

            $prepValues = array_map(fn($column) => $column->name . '=:' . $column->name, $mapping->getColumns());

            $query = "UPDATE $tableName SET " . implode(', ', $prepValues) . " WHERE " . $mapping->getId() . " = :id";

            $bind = [];
            $bind[':' . $mapping->getId()] = $object->getId();

            foreach ($mapping->getColumns() as $column) {
                // @phpstan-ignore-next-line
                $bind[':' . $column->name] = $object->{'get' . ucfirst($column->propertyName)}();
            }

            $adapter->addToTransaction($query, $bind);
        }

        $this->clearEntityUpdatesQueue();
        return $adapter->transactionQuery();
    }

    /**
     * @return int
     * @throws Exception
     */
    private function executeDeletions(): int
    {
        $adapter = $this->entityManager->getAdapter();

        /** @var mixed $object */
        foreach ($this->entityDeletions as $object) {
            if (!is_object($object)) {
                throw new Exception("$object is not a valid object");
            }

            /** @var object $object */
            $mapping = $this->mapper->resolve($object::class);
            $tableName = $mapping->getTable()->tableName;
            $query = "DELETE FROM $tableName WHERE " . $mapping->getId() . " = :id";
            $bind = [':id' => $object->getId()];

            $adapter->addToTransaction($query, $bind);
        }

        $this->clearEntityDeletionsQueue();
        return $adapter->transactionQuery();
    }

    /**
     * @param object $entity
     * @return void
     */
    public function add(object $entity): void
    {
        $oid = spl_object_id($entity);
        $this->entityInsertions[$oid] = $entity;
    }

    /**
     * @param object $entity
     * @return void
     */
    public function update(object $entity): void
    {
        $oid = spl_object_id($entity);
        $this->entityUpdates[$oid] = $entity;
    }

    /**
     * @param object $entity
     * @return void
     */
    public function delete(object $entity): void
    {
        $this->entityDeletions[] = $entity;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function commit(): int
    {
        $rowCount = 0;

        if (count($this->entityDeletions)) {
            $rowCount += $this->executeDeletions();
        }

        if (count($this->entityUpdates)) {
            $rowCount += $this->executeUpdates();
        }

        if (count($this->entityInsertions)) {
            $rowCount += $this->executeInserts();
        }

        return $rowCount;
    }

    public function clearEntityInsertionsQueue(): void
    {
        unset($this->entityInsertions);
        $this->entityInsertions = [];
    }

    public function clearEntityDeletionsQueue(): void
    {
        unset($this->entityDeletions);
        $this->entityDeletions = [];
    }

    public function clearEntityUpdatesQueue(): void
    {
        unset($this->entityUpdates);
        $this->entityUpdates = [];
    }
}