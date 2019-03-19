<?php

namespace App\Core\Application\Service;

use App\SharedKernel\Application\Request;

class GetUserBalanceRequest implements Request
{
    private $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}