<?php

declare(strict_types=1);

namespace Blog\Repository;

use Blog\Entity\EntityManager;

abstract class Repository
{
    public function __construct(
        private EntityManager $entityManager
    ) {
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
    }
}