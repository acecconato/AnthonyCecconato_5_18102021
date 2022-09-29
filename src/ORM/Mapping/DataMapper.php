<?php

namespace Blog\ORM\Mapping;

use ArrayObject;
use Blog\ORM\Mapping\Attribute\Column;
use Blog\ORM\Mapping\Attribute\Entity;
use Blog\ORM\Mapping\Attribute\Id;
use Blog\ORM\Mapping\Attribute\Table;
use Exception;
use ReflectionClass;
use ReflectionException;

class DataMapper implements MapperInterface
{
    /**
     * @var ArrayObject<string, Metadata> $mapping
     */
    private ArrayObject $mapping;

    public function __construct()
    {
        $this->mapping = new ArrayObject();
    }

    /**
     * @throws ReflectionException
     */
    public function resolve(string $entity): Metadata
    {
        if ($this->mapping->offsetExists($entity)) {
            return $this->mapping->offsetGet($entity);
        }

        $metadatas = $this->getClassMetadata($entity);

        $this->mapping->offsetSet($entity, $metadatas);

        return $metadatas;
    }

    /**
     * @param  string $fqcn
     * @return Metadata
     * @throws ReflectionException
     * @throws Exception
     */
    public function getClassMetadata(string $fqcn): Metadata
    {
        $metadatas = new Metadata();
        $reflClass = new ReflectionClass($fqcn);
        $reflAttribute = $reflClass->getAttributes();

        $classAttributesName = array_map(fn ($reflAttribute) => $reflAttribute->getName(), $reflAttribute);

        $metadatas->setFqcn($fqcn);

        foreach ($reflAttribute as $attribute) {
            $attributeInstance = $attribute->newInstance();

            switch ($attribute->getName()) {
                case Entity::class:
                    /**
                     * @var \Blog\ORM\Mapping\Attribute\Entity $attributeInstance
                    */
                    $metadatas->setEntity($attributeInstance);
                    break;
                case Table::class:
                    /**
                     * @var Table $attributeInstance
                    */
                    $metadatas->setTable($attributeInstance);
                    break;
                default:
                    throw new Exception('Unhandled attribute ' . $attribute->getName());
            }
        }

        foreach ($reflClass->getProperties() as $property) {
            foreach ($property->getAttributes() as $propAttributes) {
                switch ($propAttributes->getName()) {
                    case Column::class:
                        $propAttributesInstance = $propAttributes->newInstance();

                        // Dynamically set
                        $propAttributesInstance->propertyName = $property->getName();

                        /**
                         * @var Column $propAttributesInstance
                        */
                        $metadatas->addColumns($propAttributesInstance);
                        break;

                    case Id::class:
                        $metadatas->setId($property->getName());
                        break;
                }
            }
        }

        return $metadatas;
    }
}
