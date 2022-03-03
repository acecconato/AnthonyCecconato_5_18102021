<?php

namespace Blog\Database;

interface MapperInterface
{
    /**
     * @param string $entity
     * @return array<string>
     */
    public function mapEntity(string $entity): array;
}