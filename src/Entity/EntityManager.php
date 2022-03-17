<?php

declare(strict_types=1);

namespace Blog\Entity;

use Blog\Database\Adapter\AdapterInterface;
use Blog\Database\MapperInterface;
use Blog\ORM\Hydration\ObjectHydrator;
use Blog\ORM\UnitOfWork;
use JetBrains\PhpStorm\Pure;
use PDO;
use ReflectionException;

/**
 * // TODO: Secure SQL transactions
 */
class EntityManager
{
    private UnitOfWork $unitOfWork;

    #[Pure] public function __construct(
        private AdapterInterface $adapter,
        private MapperInterface $mapper,
        private ObjectHydrator $hydrator
    ) {
        $this->unitOfWork = new UnitOfWork($this, $this->mapper);
    }

    public function add(object $entity): self
    {
        $this->unitOfWork->add($entity);
        return $this;
    }

    public function update(object $entity): self
    {
        $this->unitOfWork->update($entity);
        return $this;
    }

    public function delete(object $entity): self
    {
        $this->unitOfWork->delete($entity);
        return $this;
    }

    public function flush(): void
    {
        $this->unitOfWork->commit();
    }

    public function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }

    /**
     * @return array<object>
     * @throws ReflectionException
     */
    public function findAll(string $entityFqcn, string $orderBy, string $orderWay): array
    {
        $mapping = $this->mapper->resolve($entityFqcn);

        $query = 'SELECT * FROM ' . $mapping->getTable()->tableName . ' ORDER BY ' . $orderBy . ' ' . $orderWay;
        $rawResults = $this->adapter->query($query)->fetchAll(PDO::FETCH_ASSOC);

        return $this->hydrator->hydrateResultSet($rawResults, $entityFqcn);
    }
}