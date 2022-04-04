<?php

namespace Blog\ORM\Mapping;

interface MapperInterface
{
    public function resolve(string $entity): Metadata;
}