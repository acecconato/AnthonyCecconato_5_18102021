<?php

declare(strict_types=1);

namespace Blog\Entity;

use Blog\Attribute\Entity;
use Blog\Database\Adapter\AdapterInterface;
use Blog\Database\MapperInterface;
use Blog\ORM\Persister\EntityPersister;
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
        $this->unitOfWork = new UnitOfWork($this, $this->mapper);
    }

    /**
     * @param object $entity
     * @return EntityManager
     */
    public function add(object $entity): self
    {
       $this->unitOfWork->add($entity);
        return $this;
    }

    /**
     * @param object $entity
     * @return EntityManager
     */
    public function update(object $entity): self
    {
        $this->unitOfWork->update($entity);
        return $this;
    }

    /**
     * @param object $entity
     * @return EntityManager
     */
    public function delete(object $entity): self
    {
        $this->unitOfWork->delete($entity);
        return $this;
    }

    public function flush(): void
    {
        $this->unitOfWork->commit();
    }

    public function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }
}