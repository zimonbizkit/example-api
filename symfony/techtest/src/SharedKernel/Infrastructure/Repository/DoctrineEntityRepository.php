<?php

namespace App\SharedKernel\Infrastructure\Repository;

use App\SharedKernel\Domain\Entity;
use Doctrine\ORM\EntityManagerInterface;

abstract class DoctrineEntityRepository
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    abstract public function getEntityClass(): string;

    /** return Entity|null */
    public function findById($id): ?object
    {
        return $this->entityManager->find(
            $this->getEntityClass(), $id
        );
    }

    public function save(Entity $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}