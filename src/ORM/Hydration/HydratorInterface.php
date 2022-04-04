<?php

namespace Blog\ORM\Hydration;

interface HydratorInterface
{
    /** Hydrate multiple objects from an array
     * @param array<array-key, array<string>> $results
     * @param string $fqcnEntity
     * @return array<object>
     */
    public function hydrateResultSet(array $results, string $fqcnEntity): array;

    /** Hydrate a single object from an array
     * @param array<string> $result
     * @param string $fqcnEntity
     * @return object
     */
    public function hydrateSingle(array $result, string $fqcnEntity): object;

    /** Extract object(s) to array
     * @param object|array<object> $entry
     * @return array<string>
     */
    public function extract(object|array $entry): array;
}