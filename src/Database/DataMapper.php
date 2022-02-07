<?php

namespace Blog\Database;

use Blog\Attribute\Entity;
use Blog\Attribute\Table;

class DataMapper implements MapperInterface
{
    /** @var string  */
    private string $tableName;

    /** @var string  */
    private string $repositoryClass;

    /** @var array<mixed>  */
    private array $mapping = [];

    public function mapEntity(object $entity): array
    {
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
                $this->$key = $arg;
            }
        }

        foreach ($reflClass->getProperties() as $property) {
            foreach ($property->getAttributes() as $attribute) {
                $this->mapping[$attribute->getName()][] = $attribute->getArguments();
            }
        }

        return [
            'tableName' => $this->tableName,
            'repositoryClass' => $this->repositoryClass,
            'data ' => $this->mapping
        ];
    }
}