<?php

namespace Blog\Database;

interface MapperInterface
{
    public function mapEntity(object $entity);
}