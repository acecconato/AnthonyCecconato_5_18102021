<?php

declare(strict_types=1);

namespace Blog\Repository;

use Blog\ORM\EntityManager;
use ReflectionException;

abstract class Repository
{
    public function __construct(
        private EntityManager $entityManager,
    ) {
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @return array<object>
     * @throws ReflectionException
     */
    public function findAll(string $orderBy = 'id', string $orderWay = 'DESC'): array
    {
        // Late static binding
        preg_match("/(\w+)Repository$/", static::class, $matches);
        $entityFqcn = 'Blog\\Entity\\' . $matches[1];

        return $this->entityManager->findAll($entityFqcn, $orderBy, $orderWay);
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
     * @param array<string> $criteria
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
     * @param array<string> $ids
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