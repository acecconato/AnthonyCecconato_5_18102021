<?php

declare(strict_types=1);

namespace Blog\Repository;

use Blog\Database\Adapter\AdapterInterface;
use Blog\Hydration\HydratorInterface;
use Blog\ORM\EntityManager;
use PDOStatement;
use ReflectionException;

abstract class Repository
{
    public function __construct(
        private readonly EntityManager $entityManager,
    ) {
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    protected function getHydrator(): HydratorInterface
    {
        return $this->entityManager->getHydrator();
    }

    protected function getAdapter(): AdapterInterface
    {
        return $this->entityManager->getAdapter();
    }

    protected function query(string $query): bool|PDOStatement
    {
        return $this->entityManager->getAdapter()->query($query);
    }

    /**
     * @return object[]
     * @throws ReflectionException
     */
    public function findAll(
        int $offset = 0,
        int $limit = 0,
        string $orderBy = 'created_at',
        string $orderWay = 'DESC'
    ): array {
        // Late static binding
        preg_match("/(\w+)Repository$/", static::class, $matches);
        $entityFqcn = 'Blog\\Entity\\' . $matches[1];

        return $this->entityManager->findAll($entityFqcn, $offset, $limit, $orderBy, $orderWay);
    }

    /**
     * @param  array<mixed> $criteria
     * @return object[]
     * @throws ReflectionException
     */
    public function findAllBy(
        array $criteria,
        string $orderBy = 'created_at',
        string $orderWay = 'DESC'
    ): array {
        // Late static binding
        preg_match("/(\w+)Repository$/", static::class, $matches);
        $entityFqcn = 'Blog\\Entity\\' . $matches[1];

        return $this->entityManager->findAllBy($entityFqcn, $criteria, $orderBy, $orderWay);
    }


    public function countAll(): int
    {
        // Late static binding
        preg_match("/(\w+)Repository$/", static::class, $matches);
        $entityFqcn = 'Blog\\Entity\\' . $matches[1];

        return $this->entityManager->countAll($entityFqcn);
    }

    /**
     * @throws ReflectionException
     */
    public function find(string $id): object|false
    {
        // Late static binding
        preg_match("/(\w+)Repository$/", static::class, $matches);
        $entityFqcn = 'Blog\\Entity\\' . $matches[1];

        return $this->entityManager->find($entityFqcn, $id);
    }

    /**
     * @param  array<string> $criteria
     * @return object|false
     * @throws ReflectionException
     */
    public function findOneBy(array $criteria): object|false
    {
        // Late static binding
        preg_match("/(\w+)Repository$/", static::class, $matches);
        $entityFqcn = 'Blog\\Entity\\' . $matches[1];

        return $this->entityManager->findOneBy($entityFqcn, $criteria);
    }

    /**
     * @param  array<string> $ids
     * @return int
     */
    public function delete(array $ids): int
    {
        // Late static binding
        preg_match("/(\w+)Repository$/", static::class, $matches);
        $entityFqcn = 'Blog\\Entity\\' . $matches[1];

        return $this->entityManager->deleteById($entityFqcn, $ids);
    }
}
