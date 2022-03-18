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
}