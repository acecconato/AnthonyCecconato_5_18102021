<?php

declare(strict_types=1);

namespace Blog\Database;

use Blog\Attribute\Column;
use Blog\Attribute\Entity;
use Blog\Attribute\Table;
use Doctrine\ORM\Mapping\Id;

class Metadata
{
    private string $fqcn;

    private Entity $entity;

    private Table $table;

    private string $id;

    /** @var array<Column> */
    private array $columns = [];

    /**
     * @return string
     */
    public function getFqcn(): string
    {
        return $this->fqcn;
    }

    /**
     * @param string $fqcn
     * @return Metadata
     */
    public function setFqcn(string $fqcn): Metadata
    {
        $this->fqcn = $fqcn;
        return $this;
    }

    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     * @return Metadata
     */
    public function setEntity(Entity $entity): Metadata
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return Table
     */
    public function getTable(): Table
    {
        return $this->table;
    }

    /**
     * @param Table $table
     * @return Metadata
     */
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
     * @return array<Column>
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param Column $column
     * @return Metadata
     */
    public function addColumns(Column $column): Metadata
    {
        $this->columns[] = $column;
        return $this;
    }
}