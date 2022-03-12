<?php

namespace Blog\Database;

interface MapperInterface
{
    public function resolve(string $entity): Metadata;
}