<?php

declare(strict_types=1);

namespace Blog\ORM\Hydration;

use Blog\Database\Metadata;
use ReflectionClass;
use ReflectionException;

class ObjectHydrator extends AbstractHydrator implements HydratorInterface
{

    /**
     * @throws ReflectionException
     */
    public function hydrateResultSet(array $results, string $fqcnEntity): array
    {
        $mapping = $this->mapper->resolve($fqcnEntity);

        return array_map(
            function ($res) use ($mapping) {
                return $this->hydrateObject($mapping, $res);
            },
            $results
        );
    }

    /**
     * @throws ReflectionException
     */
    public function hydrateSingle(array $result, string $fqcnEntity): object
    {
        $mapping = $this->mapper->resolve($fqcnEntity);

        return $this->hydrateObject($mapping, $result);
    }

    /**
     * @param array<string> $result
     * @throws ReflectionException
     */
    public function hydrateObject(Metadata $mapping, array $result): object
    {
        $reflClass = new ReflectionClass($mapping->getFqcn());
        $object = $reflClass->newInstance();

        foreach ($mapping->getColumns() as $column) {
            // @phpstan-ignore-next-line
            $setterMethod = 'set' . ucfirst($column->propertyName);
            $object->{$setterMethod}($result[$column->name]);
        }

        $object->setId($result[$mapping->getId()]);

        return $object;
    }
}