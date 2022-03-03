<?php

namespace Blog\Database;

use ArrayObject;
use Blog\Attribute\Entity;
use Blog\Attribute\Table;
use ReflectionException;

class DataMapper implements MapperInterface
{
    private string $tableName;

    private string $repositoryClass;

    /** @var array<array<string>> */
    private array $mapping = [];

    private ArrayObject $entities;

    public function __construct()
    {
        $this->entities = new ArrayObject();
    }

    /** Get the mapping of an entity
     * @param string $entity FQCN
     * @return array<string>
     * @throws ReflectionException
     */
    public function mapEntity(string $entity): array
    {
        if ($this->entities->offsetExists($entity)) {
            return $this->entities->offsetGet($entity);
        }

        $reflClass = new \ReflectionClass($entity);

        $classAttributesName = [];
        foreach ($reflClass->getAttributes() as $attribute) {
            $classAttributesName[] = $attribute->getName();
        }

        if (!in_array(Entity::class, $classAttributesName)) {
            throw new \Exception('Attribute ' . Entity::class . ' is missing from ' . $reflClass->getName());
        }

        if (!in_array(Table::class, $classAttributesName)) {
            throw new \Exception('Attribute ' . Table::class . ' is missing from ' . $reflClass->getName());
        }

        foreach ($reflClass->getAttributes() as $attribute) {
            $args = $attribute->getArguments();

            foreach ($args as $key => $arg) {
                $this->{$key} = $arg;
            }
        }

        $mapping = [];
        foreach ($reflClass->getProperties() as $property) {
            foreach ($property->getAttributes() as $attribute) {
                $mapping[] = array_merge(
                    $attribute->getArguments(),
                    ['propertyName' => $property->getName()]
                );
            }
        }

        $this->entities->offsetSet($entity, [
            'tableName' => $this->tableName,
            'repositoryClass' => $this->repositoryClass,
            'columns' => $mapping
        ]);

        return $this->entities->offsetGet($entity);
    }
}