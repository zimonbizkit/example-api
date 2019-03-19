<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\User;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\SharedKernel\Domain\Entity;
use App\SharedKernel\Infrastructure\Repository\DoctrineEntityRepository;

class UserRepository extends DoctrineEntityRepository implements UserRepositoryInterface
{
    public function getEntityClass(): string
    {
        return User::class;
    }

    public function findById($id): ?object
    {
        return parent::findById($id);
    }

    public function save(Entity $entity)
    {
        parent::save($entity);
    }
}