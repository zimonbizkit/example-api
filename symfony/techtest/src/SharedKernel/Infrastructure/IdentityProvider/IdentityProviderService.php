<?php

namespace App\SharedKernel\Infrastructure\IdentityProvider;

use App\SharedKernel\Domain\Service\IdentityProviderInterface;
use Ramsey\Uuid\Uuid;

class IdentityProviderService implements IdentityProviderInterface
{
    public function provide(): string
    {
        return Uuid::uuid4();
    }
}