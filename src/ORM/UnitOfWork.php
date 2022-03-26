<?php

declare(strict_types=1);

namespace Blog\ORM;

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

    public function executeInserts(): int
    {
        $adapter = $this->entityManager->getAdapter();

        foreach ($this->entityInsertions as $object) {
            $mapping = $this->mapper->resolve($object::class);

            $tableName = $mapping->getTable()->tableName;

            $prepValues = array_map(fn($column) => ':' . $column->name, $mapping->getColumns());

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

    public function executeUpdates(): void
    {
    }

    /**
     * @throws Exception
     */
    public function executeDeletions(): int
    {
        $adapter = $this->entityManager->getAdapter();

        /** @var mixed $item */
        foreach ($this->entityDeletions as $item) {

            if (!is_object($item) && !is_array($item)) {
                throw new Exception('The ' . __CLASS__ . ' deletion method only accepts objects and array');
            }

            if (is_object($item)) {
                /** @var object $item */
                $mapping = $this->mapper->resolve($item::class);
                $tableName = $mapping->getTable()->tableName;
                $query = "DELETE FROM $tableName WHERE " . $mapping->getId() . " = :id";
                $bind = [':id' => $item->getId()];
            }

            if (is_array($item)) {

            }

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

    public function commit(): int
    {
        $rowCount = 0;

        if (count($this->entityDeletions)) {
            $rowCount += $this->executeDeletions();
        }

//        if (count($this->entityUpdates)) {
//        $rowCount += $this->executeUpdates();
//        }

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