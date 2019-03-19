<?php

namespace App\Core\Application\Service;

use App\Core\Domain\Entity\Balance;
use App\SharedKernel\Application\Response;

class GetUserBalanceResponse implements Response
{
    /**
     * @var Balance
     */
    private $balance;

    public function __construct(Balance $balance)
    {
        $this->balance = $balance;
    }

    public function getBalanceResource(): Balance
    {
        return $this->balance;
    }


}