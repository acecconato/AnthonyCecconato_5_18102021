<?php

declare(strict_types=1);

namespace Blog\Hydration;

use Blog\ORM\Mapping\DataMapper;
use Blog\ORM\Mapping\Metadata;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;

class ObjectHydrator implements HydratorInterface
{

    public function __construct(
        protected DataMapper $mapper
    ) {
    }

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
    protected function hydrateObject(Metadata $mapping, array $result): object
    {
        $reflClass = new ReflectionClass($mapping->getFqcn());

        $object = $reflClass->newInstance();

        foreach ($mapping->getColumns() as $column) {
            // @phpstan-ignore-next-line
            $setterMethod = 'set' . ucfirst($column->propertyName);
            $object->{$setterMethod}($result[$column->name]);
        }

        if (array_key_exists($mapping->getId(), $result)) {
            $object->setId($result[$mapping->getId()]);
        }

        return $object;
    }

    public function extract(object|array $entry): array
    {
        $output = [];

        if (is_array($entry)) {
            $i = 0;
            foreach ($entry as $object) {
                $reflObj = new ReflectionObject($object);

                foreach ($reflObj->getProperties() as $prop) {
                    $getterMethod = 'get' . ucfirst($prop->getName());
                    $output[$i][$prop->name] = $object->{$getterMethod}();
                }

                $i++;
            }
        }

        if (is_object($entry)) {
            $reflObj = new ReflectionObject($entry);

            foreach ($reflObj->getProperties() as $prop) {
                $getterMethod = 'get' . ucfirst($prop->getName());
                $output[$prop->name] = $entry->{$getterMethod}();
            }
        }

        return $output;
    }
}