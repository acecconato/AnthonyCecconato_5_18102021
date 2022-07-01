<?php

declare(strict_types=1);

namespace Blog\ORM;

use Blog\Kernel;
use Blog\ORM\Mapping\Attribute\Enum\Type;
use Blog\ORM\Mapping\MapperInterface;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;
use ReflectionObject;

class UnitOfWork
{
    /** @var array<int, object> */
    private array $entityInsertions = [];

    /** @var array<int, object> */
    private array $entityUpdates = [];

    /** @var array<string> */
    private array $entityDeletions = [];

    public function __construct(
        private EntityManager $entityManager,
        private MapperInterface $mapper
    ) {
    }

    /**
     * @throws Exception
     */
    private function executeInserts(): int
    {
        $adapter = $this->entityManager->getAdapter();

        foreach ($this->entityInsertions as $object) {
            if (!is_object($object)) {
                throw new Exception("$object is not a valid object");
            }

            $mapping = $this->mapper->resolve($object::class);
            $tableName = $mapping->getTable()->tableName;
            $reflObj = new ReflectionObject($object);

            $prepValues = array_map(function ($prop) use ($object, $mapping) {
                if ($prop->isInitialized($object)) {
                    if ($column = $mapping->getColumn($prop->getName())) {
                        return ':' . $column->name;
                    }
                }

                return null;
            }, $reflObj->getProperties());

            // Remove null values
            $prepValues = array_filter($prepValues, fn($value) => ($value));

            $prepValues = implode(', ', $prepValues);

            $query = "
                INSERT INTO $tableName ( " . $mapping->getId() . ", " . str_replace(':', '', $prepValues) . " )
                VALUES( :" . $mapping->getId() . ", " . $prepValues . " )
            ";

            $bind = [];

            ($object->getId()) ?: $object->setId((string)Uuid::uuid4());
            $bind[':' . $mapping->getId()] = $object->getId();

            foreach ($mapping->getColumns() as $column) {
                foreach ($reflObj->getProperties() as $prop) {
                    // @phpstan-ignore-next-line
                    if ($prop->getName() === $column->propertyName) {
                        if ($prop->isInitialized($object)) {
                            // DateTime to string conversion
                            if ($column->type === Type::DATE) {
                                /** @var ?DateTime $datetime */
                                $datetime = $prop->getValue($object);

                                $bind[':' . $column->name] = null;

                                if ($datetime) {
                                    $bind[':' . $column->name] = $datetime->format(Kernel::DATE_FORMAT);
                                }

                                continue;
                            }

                            $bind[':' . $column->name] = $prop->getValue($object);
                        }
                    }
                }
            }

            $adapter->addToTransaction($query, $bind);
        }

        $this->clearEntityInsertionsQueue();
        return $adapter->transactionQuery();
    }

    /**
     * @throws Exception
     */
    private function executeUpdates(): int
    {
        $adapter = $this->entityManager->getAdapter();

        /** @var object $object */
        foreach ($this->entityUpdates as $object) {
            if (!is_object($object)) {
                throw new Exception("$object is not a valid object");
            }

            $mapping = $this->mapper->resolve($object::class);
            $tableName = $mapping->getTable()->tableName;

            $prepValues = array_map(fn($column) => $column->name . '=:' . $column->name, $mapping->getColumns());

            $query = "UPDATE $tableName SET " . implode(', ', $prepValues) . " WHERE " . $mapping->getId() . " = :id";

            $bind = [];
            $bind[':' . $mapping->getId()] = $object->getId();

            foreach ($mapping->getColumns() as $column) {
                // DateTime to string conversion
                if ($column->type === Type::DATE) {
                    /** @var ?DateTime $datetime */
                    $datetime = $object->{'get' . ucfirst($column->propertyName)}() ?? new DateTime();

                    $bind[':' . $column->name] = $datetime?->format(Kernel::DATE_FORMAT);

                    continue;
                }

                // @phpstan-ignore-next-line
                $bind[':' . $column->name] = $object->{'get' . ucfirst($column->propertyName)}();
            }

            $adapter->addToTransaction($query, $bind);
        }

        $this->clearEntityUpdatesQueue();
        return $adapter->transactionQuery();
    }

    /**
     * @throws Exception
     */
    private function executeDeletions(): int
    {
        $adapter = $this->entityManager->getAdapter();

        /** @var mixed $object */
        foreach ($this->entityDeletions as $object) {
            if (!is_object($object)) {
                throw new Exception("$object is not a valid object");
            }

            /** @var object $object */
            $mapping = $this->mapper->resolve($object::class);
            $tableName = $mapping->getTable()->tableName;
            $query = "DELETE FROM $tableName WHERE " . $mapping->getId() . " = :id";
            $bind = [':id' => $object->getId()];

            $adapter->addToTransaction($query, $bind);
        }

        $this->clearEntityDeletionsQueue();
        return $adapter->transactionQuery();
    }

    public function add(object $entity): void
    {
        $oid = spl_object_id($entity);
        $this->entityInsertions[$oid] = $entity;
    }

    public function update(object $entity): void
    {
        $oid = spl_object_id($entity);
        $this->entityUpdates[$oid] = $entity;
    }

    public function delete(object $entity): void
    {
        $this->entityDeletions[] = $entity;
    }

    /**
     * @throws Exception
     */
    public function commit(): int
    {
        $rowCount = 0;

        if (count($this->entityDeletions)) {
            $rowCount += $this->executeDeletions();
        }

        if (count($this->entityUpdates)) {
            $rowCount += $this->executeUpdates();
        }

        if (count($this->entityInsertions)) {
            $rowCount += $this->executeInserts();
        }

        return $rowCount;
    }

    public function clearEntityInsertionsQueue(): void
    {
        unset($this->entityInsertions);
        $this->entityInsertions = [];
    }

    public function clearEntityDeletionsQueue(): void
    {
        unset($this->entityDeletions);
        $this->entityDeletions = [];
    }

    public function clearEntityUpdatesQueue(): void
    {
        unset($this->entityUpdates);
        $this->entityUpdates = [];
    }
}
