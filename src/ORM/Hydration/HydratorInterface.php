<?php

namespace Blog\ORM\Hydration;

use Blog\Entity\AbstractEntity;

interface HydratorInterface
{
    /**
     * @param array<array-key, array<string>> $results
     * @param string $fqcnEntity
     * @return array<object>
     */
    public function hydrateResultSet(array $results, string $fqcnEntity): array;

    /**
     * @param array<string> $result
     * @param string $fqcnEntity
     * @return AbstractEntity
     */
    public function hydrateSingle(array $result, string $fqcnEntity): AbstractEntity;

    /** Extract object(s) to array
     * @param object|array<object> $entry
     * @return array<string>
     */
    public function extract(object|array $entry): array;
}