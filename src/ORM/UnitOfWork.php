<?php

declare(strict_types=1);

namespace Blog\ORM;

use Blog\Entity\EntityManager;

class UnitOfWork
{
    private const STATE_NEW = 1;
    private const STATE_REMOVED = 2;
    private const STATE_UPDATED = 3;

    /** @var array<object> */
    private array $entityInsertions = [];

    /** @var array<object> */
    private array $entityDeletions = [];

    /** @var array<object> */
    private array $entityUpdates = [];

    /** @var array<string> */
    private $entityStates = [];

    public function __construct(
        private EntityManager $entityManager
    ) {
    }

    public function executeInserts(): void
    {

    }

    public function executeUpdates(): void
    {

    }

    public function executeDeletions(): void
    {

    }

    public function commit(object $entity): void
    {

    }

    public function persist(object $entity)
    {
        $oid = spl_object_id($entity);


    }
}