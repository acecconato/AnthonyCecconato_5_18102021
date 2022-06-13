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
     * @param array<string> $data
     * @throws ReflectionException
     * @throws Exception
     */
    protected function hydrateObject(Metadata $mapping, array $data): object
    {
        $reflClass = new ReflectionClass($mapping->getFqcn());
        $object = $reflClass->newInstance();

        $reflObj = new ReflectionObject($object);
        $defaultProperties = $reflObj->getDefaultProperties();

        foreach ($reflClass->getProperties() as $prop) {
            if (array_key_exists($prop->getName(), $data)) {
                $prop->setValue($object, $data[$prop->getName()]);
                continue;
            }

            $setter = 'set' . ucfirst($prop->getName());

            // Handle "property must not be accessed before initialization" case
            if (!array_key_exists($prop->getName(), $defaultProperties)) {
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