<?php

declare(strict_types=1);

namespace Blog\ORM;

use Blog\Database\MapperInterface;
use Blog\Entity\EntityManager;
use Blog\ORM\Persister\EntityPersister;

class UnitOfWork
{
    /** @var array<string, object> */
    private array $entityInsertions = [];

    /** @var array<string, object> */
    private array $entityUpdates = [];

    /** @var array<string> */
    private array $entityDeletions = [];

    private EntityPersister $entityPersister;

    public function __construct(
        private EntityManager $entityManager,
        private MapperInterface $mapper
    ) {
        $this->entityPersister = new EntityPersister($this->entityManager, $this->entityManager->getAdapter());
    }

    public function executeInserts(): void
    {
        $adapter = $this->entityManager->getAdapter();

        $toInsert = [];
        $mapping = [];

        foreach ($this->entityInsertions as $oid => $entity) {
            $mapping[$entity::class] = $this->mapper->mapEntity($entity::class);
            $toInsert[$entity::class][] = $entity;
        }

        $queries = [];
        foreach ($toInsert as $fqcn => $entities) {
            $properties = array_map(fn($prop) => $prop['propertyName'], $mapping[$fqcn]['columns']);
            $properties = ':' . implode(', :', $properties);
            $rawProperties = explode(', ', str_replace([':'], '', $properties));

            foreach ($entities as $entity) {
                $values = array_combine(
                    $rawProperties,
                    array_map(fn($prop) => strip_tags($entity->{'get' . ucfirst($prop)}()), $rawProperties)
                );

                $queries[] = [
                    'INSERT INTO ' . $mapping[$fqcn]['tableName'] . ' VALUES ( ' . $properties . ' )',
                    $values
                ];
            }
        }

        dump($queries);

        die();

        $adapter->transactionQuery($queries);

        die();
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
}