<?php


namespace App\SharedKernel\Domain\Service;


interface IdentityProviderInterface
{
    public function provide(): string;
}