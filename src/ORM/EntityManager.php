<?php

declare(strict_types=1);

namespace Blog\ORM;

use Blog\Database\Adapter\AdapterInterface;
use Blog\Entity\AbstractEntity;
use Blog\ORM\Hydration\HydratorInterface;
use Blog\ORM\Hydration\ObjectHydrator;
use Blog\ORM\Mapping\MapperInterface;
use PDO;
use ReflectionException;

class EntityManager
{
    private UnitOfWork $unitOfWork;

    public function __construct(
        private AdapterInterface $adapter,
        private MapperInterface $mapper,
        private ObjectHydrator $hydrator
    ) {
        $this->unitOfWork = new UnitOfWork($this, $this->mapper);
    }

    public function add(AbstractEntity $entity): self
    {
        $this->unitOfWork->add($entity);
        return $this;
    }

    public function update(AbstractEntity $entity): self
    {
        $this->unitOfWork->update($entity);
        return $this;
    }

    public function delete(AbstractEntity $entity): self
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
     * @return array<AbstractEntity>
     * @throws ReflectionException
     */
    public function findAll(string $entityFqcn, string $orderBy, string $orderWay): array
    {
        $mapping = $this->mapper->resolve($entityFqcn);

        $tableName = addslashes($mapping->getTable()->tableName);
        $orderBy = addslashes($orderBy);
        $orderWay = addslashes($orderWay);

        $query = "SELECT * FROM  $tableName ORDER BY $orderBy $orderWay";
        $rawResults = $this->adapter->query($query)->fetchAll(PDO::FETCH_ASSOC);

        return $this->hydrator->hydrateResultSet($rawResults, $entityFqcn);
    }

    /**
     * @throws ReflectionException
     */
    public function find(string $entityFqcn, string $id): AbstractEntity|false
    {
        $mapping = $this->mapper->resolve($entityFqcn);

        $tableName = addslashes($mapping->getTable()->tableName);
        $idColumn = addslashes($mapping->getId());
        $id = addslashes($id);

        $query = "SELECT * FROM $tableName WHERE $idColumn='" . $id . "'";

        $rawResult = $this->adapter->query($query)->fetch(PDO::FETCH_ASSOC);

        if (!$rawResult) {
            return false;
        }

        return $this->hydrator->hydrateSingle($rawResult, $entityFqcn);
    }

    /**
     * @param string $entityFqcn
     * @param array<string> $criteria
     * @return AbstractEntity|false
     * @throws ReflectionException
     */
    public function findOneBy(string $entityFqcn, array $criteria = []): AbstractEntity|false
    {
        $mapping = $this->mapper->resolve($entityFqcn);

        $tableName = addslashes($mapping->getTable()->tableName);
        array_walk($criteria, fn($value) => addslashes($value));

        $query = "SELECT * FROM $tableName";

        foreach ($criteria as $key => $criterion) {
            if ($key === array_key_first($criteria)) {
                $query .= " WHERE $key='" . $criterion . "'";
            } else {
                $query .= " AND $key='" . $criterion . "'";
            }
        }

        $rawResult = $this->adapter->query($query)->fetch(PDO::FETCH_ASSOC);

        if (!$rawResult) {
            return false;
        }

        return $this->hydrator->hydrateSingle($rawResult, $entityFqcn);
    }
}