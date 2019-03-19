<?php

namespace App\Core\Domain\Repository;

use App\SharedKernel\Domain\Entity;

interface UserRepositoryInterface
{
    public function findById($id): ?object;

    public function save(Entity $entity);
}