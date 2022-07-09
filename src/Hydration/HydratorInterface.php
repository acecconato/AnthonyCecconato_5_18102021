<?php

namespace Blog\Hydration;

interface HydratorInterface
{
    /** Hydrate multiple objects from an array
     * @param array<array-key, array<string>> $results
     * @param string $fqcnEntity
     * @return array<object>
     */
    public function hydrateResultSet(array $results, string $fqcnEntity): array;

    /** Hydrate a single object from an array
     * @param array<string> $data
     * @param object $object
     * @return object
     */
    public function hydrateSingle(array $data, object $object): object;

    /** Extract object(s) to array
     * @param object|array<object> $entry
     * @return array<string>
     */
    public function extract(object|array $entry): array;
}