<?php

declare(strict_types=1);

namespace Blog\ORM;

use Blog\Attribute\Enum\Type;
use Blog\Database\MapperInterface;
use Blog\Entity\EntityManager;
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

            $tableName = strip_tags($mapping->getTable()->tableName);

            $prepValues = array_map(fn($column) => ':' . $column->name, $mapping->getColumns());

            $query = "
                INSERT INTO $tableName 
                VALUES ( :" . $mapping->getId() . ", " . strip_tags(implode(', ', $prepValues)) . " )
            ";

            $bind = [];
            $uuid = (string)Uuid::uuid4();
            $bind[':' . $mapping->getId()] = $uuid;

            $object->setId($uuid);

            foreach ($mapping->getColumns() as $column) {
                // @phpstan-ignore-next-line
                $bind[':' . $column->name] = $object->{'get' . ucfirst($column->propertyName)}();
            }

            $adapter->addToTransaction($query, $bind);
            $this->clearEntityInsertionsQueue();
        }

        return $adapter->transactionQuery();
    }

    public function executeUpdates(): void
    {
    }

    public function executeDeletions(): void
    {
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

    public function commit(): void
    {
        $this->executeInserts();
    }

    public function clearEntityInsertionsQueue(): void
    {
        unset($this->entityInsertions);
        $this->entityInsertions = [];
    }
}