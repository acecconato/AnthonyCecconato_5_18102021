<?php

declare(strict_types=1);

namespace Blog\ORM\Persister;

use Blog\Database\Adapter\AdapterInterface;
use Blog\Entity\EntityManager;

class EntityPersister
{
    public function __construct(
        private EntityManager $entityManager,
        private AdapterInterface $adapter
    ) {
    }

    public function executeInserts(array $entities, array $mapping)
    {
        dump($entities);
    }
}