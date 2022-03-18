<?php

declare(strict_types=1);

namespace Blog\Repository;

use Blog\Entity\AbstractEntity;
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
    public function find(string $id): AbstractEntity|false
    {
        // Late static binding
        preg_match("/(\w+)Repository$/", static::class, $matches);
        $entityFqcn = 'Blog\\Entity\\' . $matches[1];

        return $this->entityManager->find($entityFqcn, $id);
    }

    /**
     * @param array<string> $criteria
     * @return AbstractEntity|false
     * @throws ReflectionException
     */
    public function findOneBy(array $criteria): AbstractEntity|false
    {
        // Late static binding
        preg_match("/(\w+)Repository$/", static::class, $matches);
        $entityFqcn = 'Blog\\Entity\\' . $matches[1];

        return $this->entityManager->findOneBy($entityFqcn, $criteria);
    }
}