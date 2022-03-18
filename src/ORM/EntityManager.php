<?php

declare(strict_types=1);

namespace Blog\ORM;

use Blog\Database\Adapter\AdapterInterface;
use Blog\ORM\Hydration\HydratorInterface;
use Blog\ORM\Hydration\ObjectHydrator;
use Blog\ORM\Mapping\MapperInterface;
use JetBrains\PhpStorm\Pure;
use PDO;
use ReflectionException;

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

    public function getHydrator(): HydratorInterface
    {
        return $this->hydrator;
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