<?php

declare(strict_types=1);

namespace Blog\Hydration;

use Blog\ORM\Mapping\Attribute\Enum\Type;
use Blog\ORM\Mapping\DataMapper;
use Blog\ORM\Mapping\Metadata;
use DateTime;
use Exception;
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
                $object = (new ReflectionClass($mapping->getFqcn()))->newInstance();
                return $this->hydrateSingle($res, $object);
            },
            $results
        );
    }

    /**
     * @param array<string> $data
     * @param object $object
     * @return object
     * @throws ReflectionException
     * @throws Exception
     */
    public function hydrateSingle(array $data, object $object): object
    {
        $mapping = $this->mapper->resolve($object::class);

        $reflClass = new ReflectionClass($mapping->getFqcn());

        $reflObj = new ReflectionObject($object);
        $defaultProperties = $reflObj->getDefaultProperties();

        $columns = [];
        foreach ($mapping->getColumns() as $column) {
            // @phpstan-ignore-next-line
            $columns[$column->propertyName] = $column->name;
        }

        foreach ($reflClass->getProperties() as $prop) {
            if (array_key_exists($prop->getName(), $data)) {
                $prop->setValue($object, $data[$prop->getName()]);
            }

            if (array_key_exists($prop->getName(), $columns)) {
                if (array_key_exists($column = $columns[$prop->getName()], $data)) {
                    $column = $mapping->getColumn($column);

                    if ($column->type === Type::DATE) {
                        if (array_key_exists($column->name, $data) && $data[$column->name]) {
                            $prop->setValue($object, new DateTime($data[$column->name]));
                            continue;
                        }
                    }

                    $prop->setValue($object, $data[$columns[$prop->getName()]]);
                }
            }

            $setter = 'set' . ucfirst($prop->getName());

            // Handle "property must not be accessed before initialization" case
            if (!array_key_exists($prop->getName(), $defaultProperties) && !$prop->isInitialized($object)) {
                $nbParams = $reflClass->getMethod($setter)->getNumberOfParameters();
                $hasParameter = (bool)$reflClass->getMethod($setter)->getNumberOfParameters();

                if ($nbParams > 1) {
                    continue;
                }

                if (!$hasParameter) {
                    $object->{$setter}();
                    continue;
                }

                $parameter = $reflClass->getMethod($setter)->getParameters()[0];

                if ($reflClass->getMethod($setter)->getNumberOfRequiredParameters() === 1 && $parameter->allowsNull()) {
                    $object->{$setter}(null);
                }
            }
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

                foreach ($reflObj->getProperties() as $property) {
                    if ($property->isInitialized($object)) {
                        $output[$i][$property->getName()] = $property->getValue($object);
                    }
                }

                $i++;
            }
        }

        if (is_object($entry)) {
            $reflObj = new ReflectionObject($entry);

            foreach ($reflObj->getProperties() as $property) {
                if ($property->isInitialized($entry)) {
                    $output[$property->getName()] = $property->getValue($entry);
                }
            }
        }

        return $output;
    }
}