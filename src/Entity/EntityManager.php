<?php

declare(strict_types=1);

namespace Blog\Entity;

use Blog\Attribute\Entity;
use Blog\Database\Adapter\AdapterInterface;
use Blog\Database\MapperInterface;
use Blog\ORM\UnitOfWork;
use JetBrains\PhpStorm\Pure;

/**
 * // TODO: Secure SQL transactions
 */
class EntityManager
{
    /** @var UnitOfWork */
    private UnitOfWork $unitOfWork;

    public function __construct(
        private AdapterInterface $adapter,
        private MapperInterface $mapper
    ) {
        $this->unitOfWork = new UnitOfWork($this);
    }

    /**
     * @return AdapterInterface
     */
    public function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }

    /**
     * @param AdapterInterface $adapter
     * @return EntityManager
     */
    public function setAdapter(AdapterInterface $adapter): self
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * @param object $entity
     * @return $this
     */
    public function persist(object $entity): self
    {
        $this->insertions[] = $entity;

//        $this->unitOfWork->

        return $this;
    }

    public function flush(): void
    {

    }

    public function getConnection(): AdapterInterface
    {
        return $this->getAdapter();
    }
}