<?php

declare(strict_types=1);

namespace Blog\ORM\Mapping;

use Blog\ORM\Mapping\Attribute\Column;
use Blog\ORM\Mapping\Attribute\Entity;
use Blog\ORM\Mapping\Attribute\Table;

class Metadata
{
    private string $fqcn;

    private Entity $entity;

    private Table $table;

    private string $id;

    /** @var Column[] */
    private array $columns = [];

    public function getFqcn(): string
    {
        return $this->fqcn;
    }

    public function setFqcn(string $fqcn): Metadata
    {
        $this->fqcn = $fqcn;
        return $this;
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }

    public function setEntity(Entity $entity): Metadata
    {
        $this->entity = $entity;
        return $this;
    }

    public function getTable(): Table
    {
        return $this->table;
    }

    public function setTable(Table $table): Metadata
    {
        $this->table = $table;
        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Metadata
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function addColumns(Column $column): Metadata
    {
        $this->columns[] = $column;
        return $this;
    }
}